<?php
/**
 * SAC Event - Database Table Engine & Reindex Management
 * Script for converting MySQL table engines to InnoDB, reindexing, and optimizing tables.
 * 
 * Supports both Web UI (Admin Dashboard) and CLI execution (php reindex_table.php / Cronjob).
 */

date_default_timezone_set("Asia/Bangkok");
@set_time_limit(0);

$isCli = (php_sapi_name() === 'cli');

require_once __DIR__ . '/config/connect_db.php';

/**
 * Format bytes to human readable format (B, KB, MB, GB)
 */
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// -------------------------------------------------------------
// 1. CLI Execution Handler (php reindex_table.php / cronjob)
// -------------------------------------------------------------
if ($isCli) {
    $dbName = defined('DB_NAME') ? DB_NAME : 'Database';
    echo PHP_EOL . "========================================================" . PHP_EOL;
    echo " SAC Event - Database Table Optimizer & InnoDB Converter" . PHP_EOL;
    echo "========================================================" . PHP_EOL;
    echo "Database: {$dbName}" . PHP_EOL;
    echo "Target Engine: InnoDB" . PHP_EOL;
    echo "Started at: " . date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

    try {
        $stmt = $conn->query(
            "SELECT TABLE_NAME, TABLE_TYPE, ENGINE 
             FROM information_schema.TABLES 
             WHERE TABLE_SCHEMA = DATABASE() 
               AND TABLE_TYPE = 'BASE TABLE'
             ORDER BY TABLE_NAME"
        );
        $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total = count($tables);

        if ($total === 0) {
            echo "[!] ไม่พบตารางในฐานข้อมูล" . PHP_EOL;
            exit(0);
        }

        echo "พบตารางทั้งหมด: {$total} ตาราง กำลังดำเนินการ..." . PHP_EOL . PHP_EOL;

        $successCount = 0;
        $errorCount = 0;
        $totalSaved = 0;
        $totalStartTime = microtime(true);
        $logOutput = "SAC Event - Database Optimization Log\nDate: " . date('Y-m-d H:i:s') . "\n" . str_repeat("=", 60) . "\n";

        $i = 0;
        foreach ($tables as $row) {
            $i++;
            $table = $row['TABLE_NAME'];
            $oldEngine = strtoupper($row['ENGINE'] ?? 'UNKNOWN');
            
            echo sprintf("[%d/%d] ตาราง `%s` (ปัจจุบัน: %s) -> InnoDB ... ", $i, $total, $table, $oldEngine);

            try {
                // วัดขนาดก่อน
                $stmtSize = $conn->prepare(
                    "SELECT ROUND(((data_length + index_length) / 1024 / 1024), 2) 
                     FROM information_schema.TABLES 
                     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table"
                );
                $stmtSize->execute(['table' => $table]);
                $sizeBefore = (float)$stmtSize->fetchColumn();

                // 1. ALTER TABLE ENGINE = InnoDB
                $conn->exec("ALTER TABLE `{$table}` ENGINE = InnoDB");

                // 2. ANALYZE TABLE (Re-indexes & updates key statistics)
                $conn->query("ANALYZE TABLE `{$table}`")->closeCursor();

                // 3. OPTIMIZE TABLE (Reclaims space & defragments)
                $conn->query("OPTIMIZE TABLE `{$table}`")->closeCursor();

                // วัดขนาดหลัง
                $stmtSize->execute(['table' => $table]);
                $sizeAfter = (float)$stmtSize->fetchColumn();

                $saved = max(0, $sizeBefore - $sizeAfter);
                $totalSaved += $saved;
                $engineStatus = ($oldEngine !== 'INNODB') ? "[{$oldEngine} -> InnoDB]" : "[InnoDB]";

                $line = "สำเร็จ {$engineStatus} [{$sizeBefore} MB -> {$sizeAfter} MB] ลดไป: " . round($saved, 2) . " MB";
                echo $line . PHP_EOL;
                $logOutput .= "[OK] `{$table}`: {$line}\n";
                $successCount++;
            } catch (PDOException $e) {
                $errLine = "ผิดพลาด: " . $e->getMessage();
                echo $errLine . PHP_EOL;
                $logOutput .= "[ERROR] `{$table}`: {$errLine}\n";
                $errorCount++;
            }
        }

        $totalElapsed = round(microtime(true) - $totalStartTime, 2);
        $summary = PHP_EOL . str_repeat("-", 60) . PHP_EOL;
        $summary .= "สรุปผลการทำงาน:" . PHP_EOL;
        $summary .= " - สำเร็จ: {$successCount} ตาราง" . PHP_EOL;
        $summary .= " - ผิดพลาด: {$errorCount} ตาราง" . PHP_EOL;
        $summary .= " - พื้นที่ที่ประหยัดได้: " . round($totalSaved, 2) . " MB" . PHP_EOL;
        $summary .= " - ใช้เวลาทั้งหมด: {$totalElapsed} วินาที" . PHP_EOL;
        $summary .= str_repeat("=", 60) . PHP_EOL;

        echo $summary;
        $logOutput .= $summary;

        @file_put_contents(__DIR__ . '/reindex_log.txt', $logOutput);
        exit($errorCount > 0 ? 1 : 0);

    } catch (Exception $e) {
        echo "เกิดข้อผิดพลาดร้ายแรง: " . $e->getMessage() . PHP_EOL;
        exit(1);
    }
}

// -------------------------------------------------------------
// 2. Web API Endpoint: AJAX Table Optimize / Reindex
// -------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] == 'optimize' && isset($_GET['table'])) {
    header('Content-Type: application/json; charset=utf-8');
    $table = trim($_GET['table']);
    $response = [
        'success' => false,
        'skipped' => false,
        'before' => 0,
        'after' => 0,
        'old_engine' => '',
        'new_engine' => 'InnoDB',
        'message' => ''
    ];

    try {
        // ตรวจสอบ TABLE_TYPE และ ENGINE
        $infoStmt = $conn->prepare(
            "SELECT TABLE_TYPE, ENGINE 
             FROM information_schema.TABLES 
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table"
        );
        $infoStmt->execute(['table' => $table]);
        $info = $infoStmt->fetch(PDO::FETCH_ASSOC);

        if (!$info) {
            $response['message'] = "❌ ไม่พบตาราง `{$table}` ในฐานข้อมูล";
            echo json_encode($response);
            exit;
        }

        // ข้าม VIEW
        if ($info['TABLE_TYPE'] === 'VIEW') {
            $response['skipped'] = true;
            $response['success'] = true;
            $response['message'] = "⏭️ ข้าม: `{$table}` [VIEW]";
            echo json_encode($response);
            exit;
        }

        $oldEngine = strtoupper($info['ENGINE'] ?? 'UNKNOWN');
        $response['old_engine'] = $oldEngine;

        // วัดขนาดก่อน
        $sizeQuery = "SELECT ROUND(((data_length + index_length) / 1024 / 1024), 2) 
                      FROM information_schema.TABLES 
                      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table";
        $stmtSize = $conn->prepare($sizeQuery);
        $stmtSize->execute(['table' => $table]);
        $response['before'] = (float)$stmtSize->fetchColumn();

        // 1. ALTER TABLE ENGINE = InnoDB
        $conn->exec("ALTER TABLE `{$table}` ENGINE = InnoDB");

        // 2. ANALYZE TABLE (อัปเดตสถิติดัชนี)
        $analyzeResult = $conn->query("ANALYZE TABLE `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        $analyzeMsg = $analyzeResult[0]['Msg_text'] ?? 'OK';

        // 3. OPTIMIZE TABLE (จัดเรียงพื้นที่ตารางใหม่)
        $optResult = $conn->query("OPTIMIZE TABLE `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        $optMsg = $optResult[0]['Msg_text'] ?? 'OK';

        // วัดขนาดหลัง
        $stmtSize->execute(['table' => $table]);
        $response['after'] = (float)$stmtSize->fetchColumn();

        $response['success'] = true;
        $saved = max(0, $response['before'] - $response['after']);
        $engineStatus = ($oldEngine !== 'INNODB') ? "[{$oldEngine} → InnoDB]" : "[InnoDB]";
        $response['message'] = "✅ {$engineStatus} `{$table}` [{$response['before']} MB → {$response['after']} MB] ลดไป: " . round($saved, 2) . " MB | ANALYZE: {$analyzeMsg} | OPTIMIZE: {$optMsg}";

    } catch (PDOException $e) {
        $response['message'] = "❌ ผิดพลาด: `{$table}` - " . $e->getMessage();
    }

    echo json_encode($response);
    exit;
}

// -------------------------------------------------------------
// 3. Web API Endpoint: Apply Recommended Indexes
// -------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] == 'apply_indexes') {
    header('Content-Type: application/json; charset=utf-8');
    $response = ['success' => false, 'messages' => []];

    try {
        $recommendedIndexes = [
            [
                'table' => 'evs_event_checkin',
                'name' => 'idx_chk_status',
                'columns' => '`check_in_status`(10)'
            ],
            [
                'table' => 'evs_event_checkin',
                'name' => 'idx_chk_cust_id',
                'columns' => '`cust_id`(50)'
            ],
            [
                'table' => 'evs_event_checkin',
                'name' => 'idx_chk_table_num',
                'columns' => '`table_number`(20)'
            ],
            [
                'table' => 'evs_event_checkin',
                'name' => 'idx_chk_status_tbl',
                'columns' => '`check_in_status`(10), `table_number`(20)'
            ],
            [
                'table' => 'evs_customer',
                'name' => 'idx_cust_code',
                'columns' => '`cust_id`(50)'
            ],
            [
                'table' => 'evs_customer',
                'name' => 'idx_cust_phone',
                'columns' => '`phone`(50)'
            ],
            [
                'table' => 'evs_sale_name',
                'name' => 'idx_sale_line_id',
                'columns' => '`sale_line_user_id`(100)'
            ],
            [
                'table' => 'ims_user',
                'name' => 'idx_user_account',
                'columns' => '`user_id`(50), `account_type`(20)'
            ]
        ];

        foreach ($recommendedIndexes as $idx) {
            // ตรวจสอบว่ามีตารางนี้อยู่หรือไม่
            $tblCheck = $conn->prepare("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :tbl AND TABLE_TYPE = 'BASE TABLE'");
            $tblCheck->execute(['tbl' => $idx['table']]);
            if ($tblCheck->fetchColumn() == 0) {
                continue;
            }

            // ตรวจสอบว่ามี Index นี้อยู่แล้วหรือไม่
            $check = $conn->prepare("
                SELECT COUNT(*) 
                FROM information_schema.statistics 
                WHERE table_schema = DATABASE() 
                  AND table_name = :table 
                  AND index_name = :name
            ");
            $check->execute(['table' => $idx['table'], 'name' => $idx['name']]);
            $exists = $check->fetchColumn();

            if (!$exists) {
                $conn->exec("CREATE INDEX `{$idx['name']}` ON `{$idx['table']}` ({$idx['columns']})");
                $response['messages'][] = "✅ สร้าง Index `{$idx['name']}` บนตาราง `{$idx['table']}` ({$idx['columns']}) เรียบร้อยแล้ว";
            } else {
                $response['messages'][] = "ℹ️ Index `{$idx['name']}` มีอยู่แล้วบนตาราง `{$idx['table']}`";
            }
        }

        $response['success'] = true;
    } catch (Exception $e) {
        $response['messages'][] = "❌ เกิดข้อผิดพลาด: " . $e->getMessage();
    }

    echo json_encode($response);
    exit;
}

// -------------------------------------------------------------
// 4. Web UI Page View
// -------------------------------------------------------------
include('includes/Header.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
    exit;
} else {
    // ดึงข้อมูลตารางทั้งหมดในฐานข้อมูล
    $stmt = $conn->query(
        "SELECT 
            TABLE_NAME, 
            ENGINE, 
            TABLE_TYPE,
            TABLE_ROWS, 
            DATA_LENGTH, 
            INDEX_LENGTH, 
            DATA_FREE,
            TABLE_COLLATION, 
            CREATE_TIME,
            UPDATE_TIME,
            TABLE_COMMENT
        FROM information_schema.TABLES 
        WHERE TABLE_SCHEMA = DATABASE() 
        ORDER BY TABLE_NAME ASC"
    );
    $allTables = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $baseTables = array_values(array_filter($allTables, fn($t) => ($t['TABLE_TYPE'] ?? '') === 'BASE TABLE'));
    $tableNames = array_column($baseTables, 'TABLE_NAME');
    $baseCount = count($baseTables);
    $totalCount = $baseCount;
    $viewCount = count($allTables) - $baseCount;

    $innodbCount = 0;
    $myisamCount = 0;
    $otherCount = 0;
    $totalRows = 0;
    $totalDataSize = 0;
    $totalIndexSize = 0;

    foreach ($baseTables as $t) {
        $eng = strtoupper($t['ENGINE'] ?? '');
        if ($eng === 'INNODB') {
            $innodbCount++;
        } elseif ($eng === 'MYISAM') {
            $myisamCount++;
        } else {
            $otherCount++;
        }
        $totalRows += intval($t['TABLE_ROWS'] ?? 0);
        $totalDataSize += intval($t['DATA_LENGTH'] ?? 0);
        $totalIndexSize += intval($t['INDEX_LENGTH'] ?? 0);
    }

    $dashboard_url = isset($_SESSION['dashboard_page']) && !empty($_SESSION['dashboard_page']) ? $_SESSION['dashboard_page'] : 'Dashboard_admin.php';
    ?>

    <!DOCTYPE html>
    <html lang="th">
    <head>
        <style>
            .sidebar-lock {
                position: fixed;
                top: 0; left: 0;
                width: 250px; height: 100%;
                background: rgba(0,0,0,0.1);
                z-index: 9999;
                cursor: not-allowed;
                display: none;
            }
            .working-overlay {
                pointer-events: none;
                opacity: 0.7;
            }
            .badge-innodb {
                background-color: #d4edda;
                color: #155724;
                font-weight: 600;
                padding: 4px 8px;
                border-radius: 4px;
            }
            .badge-myisam {
                background-color: #fff3cd;
                color: #856404;
                font-weight: 600;
                padding: 4px 8px;
                border-radius: 4px;
            }
            .badge-other {
                background-color: #e2e3e5;
                color: #383d41;
                font-weight: 600;
                padding: 4px 8px;
                border-radius: 4px;
            }
            .log-terminal {
                background-color: #1e1e1e;
                color: #dcdccc;
                padding: 20px;
                border-radius: 8px;
                height: 350px;
                overflow-y: auto;
                font-family: 'Consolas', 'Courier New', monospace;
                font-size: 13px;
                line-height: 1.6;
                text-align: left;
            }
        </style>
    </head>
    <body id="page-top">
    <div id="lock-overlay" class="sidebar-lock"></div>

    <div id="wrapper">
        <?php include('includes/Side-Bar.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('includes/Top-Bar.php'); ?>

                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h4 mb-0 text-gray-800">
                            <i class="fas fa-database text-primary mr-2"></i>Database Optimization & Engine Management
                        </h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo $dashboard_url; ?>">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reindex Table & InnoDB</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas fa-cogs mr-1"></i> MySQL Table Optimizer (แปลงเป็น InnoDB ทุก Table)
                                    </h6>
                                    <a href="<?php echo $dashboard_url; ?>" class="btn btn-sm btn-light shadow-sm text-primary font-weight-bold">
                                        <i class="fas fa-home fa-sm"></i> Dashboard
                                    </a>
                                </div>
                                <div class="card-body">
                                    <!-- Summary KPI Cards -->
                                    <div class="row text-center mb-4">
                                        <div class="col-md-3 col-6 border-right mb-2">
                                            <span class="text-muted small">ตารางทั้งหมด (BASE TABLE)</span>
                                            <div class="h3 font-weight-bold text-dark"><?php echo $baseCount; ?></div>
                                            <div class="small text-muted"><i class="fas fa-database"></i> <?php echo defined('DB_NAME') ? DB_NAME : 'sac_event'; ?></div>
                                        </div>
                                        <div class="col-md-3 col-6 border-right mb-2">
                                            <span class="text-muted small">ตาราง InnoDB</span>
                                            <div class="h3 font-weight-bold text-success" id="stat-innodb-count"><?php echo $innodbCount; ?></div>
                                            <div class="small <?php echo ($innodbCount === $baseCount) ? 'text-success' : 'text-warning'; ?>">
                                                <?php echo ($innodbCount === $baseCount) ? '✅ ครบทุกตารางแล้ว' : '⚠️ มีตารางที่ต้องแปลง'; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6 border-right mb-2">
                                            <span class="text-muted small">ตาราง MyISAM / อื่นๆ</span>
                                            <div class="h3 font-weight-bold <?php echo ($myisamCount > 0) ? 'text-warning' : 'text-muted'; ?>" id="stat-myisam-count">
                                                <?php echo ($myisamCount + $otherCount); ?>
                                            </div>
                                            <div class="small text-muted">VIEW: <?php echo $viewCount; ?> (ข้ามอัตโนมัติ)</div>
                                        </div>
                                        <div class="col-md-3 col-6 mb-2">
                                            <span class="text-muted small">Total Space Saved</span>
                                            <div class="h3 font-weight-bold text-success"><span id="total-saved">0.00</span> MB</div>
                                            <div class="small text-muted">รวม: <?php echo formatBytes($totalDataSize + $totalIndexSize); ?> (<?php echo number_format($totalRows); ?> แถว)</div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="text-center mb-4">
                                        <button id="start-btn" class="btn btn-primary btn-lg px-4 mr-2 shadow-sm font-weight-bold">
                                            <i class="fas fa-play mr-2"></i>เริ่มรัน Optimize & ปรับ InnoDB ทุกตาราง
                                        </button>
                                        <button id="index-btn" class="btn btn-info btn-lg px-4 mr-2 shadow-sm font-weight-bold">
                                            <i class="fas fa-key mr-2"></i>สร้าง/ปรับปรุง Index เพิ่มประสิทธิภาพ
                                        </button>
                                        <div id="after-action-btns" class="d-none mt-2">
                                            <button id="reset-btn" class="btn btn-warning btn-lg px-4 mr-2 shadow-sm font-weight-bold">
                                                <i class="fas fa-undo mr-2"></i>Reset หน้าจอ
                                            </button>
                                            <button id="download-btn" class="btn btn-outline-info btn-lg px-4 mr-2 shadow-sm">
                                                <i class="fas fa-file-alt mr-2"></i>ดาวน์โหลดผลลัพธ์ (.txt)
                                            </button>
                                            <a href="<?php echo $dashboard_url; ?>" class="btn btn-outline-secondary btn-lg px-4 shadow-sm">
                                                <i class="fas fa-home mr-2"></i>กลับหน้าหลัก
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Progress & Live Terminal Window -->
                                    <div id="ui-section" class="d-none mb-4">
                                        <div class="progress mb-3" style="height: 25px;">
                                            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success font-weight-bold" style="width: 0%;">0%</div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span id="status-text" class="font-weight-bold text-primary small">รอดำเนินการ...</span>
                                            <span id="count-text" class="text-muted small">0 / <?php echo $totalCount; ?></span>
                                        </div>
                                        <div id="log-window" class="log-terminal">
                                            <div style="color: #666;">--- กดปุ่มด้านบนเพื่อเริ่มกระบวนการ ---</div>
                                        </div>
                                    </div>

                                    <!-- Detailed Tables List -->
                                    <div class="table-responsive rounded border mb-4">
                                        <table class="table table-hover align-items-center table-flush mb-0" id="tableListGrid">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="width: 50px;">#</th>
                                                    <th>ชื่อตาราง (Table Name)</th>
                                                    <th>Engine ปัจจุบัน</th>
                                                    <th class="text-right">จำนวนแถว (Rows)</th>
                                                    <th class="text-right">Data Size</th>
                                                    <th class="text-right">Index Size</th>
                                                    <th class="text-right">Total Size</th>
                                                    <th>Collation</th>
                                                    <th class="text-center" style="width: 150px;">การจัดการ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($baseTables)): ?>
                                                    <tr>
                                                        <td colspan="9" class="text-center py-4 text-muted">ไม่พบตารางในฐานข้อมูล</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php $rowNo = 0; foreach ($baseTables as $tbl): $rowNo++; ?>
                                                        <?php 
                                                            $tName = $tbl['TABLE_NAME'];
                                                            $engine = $tbl['ENGINE'] ?? 'Unknown';
                                                            $rows = intval($tbl['TABLE_ROWS'] ?? 0);
                                                            $dataLen = intval($tbl['DATA_LENGTH'] ?? 0);
                                                            $indexLen = intval($tbl['INDEX_LENGTH'] ?? 0);
                                                            $totalLen = $dataLen + $indexLen;
                                                            $collation = $tbl['TABLE_COLLATION'] ?? '-';
                                                            $isInnoDB = strtoupper($engine) === 'INNODB';
                                                        ?>
                                                        <tr id="row-<?php echo htmlspecialchars($tName); ?>">
                                                            <td><?php echo $rowNo; ?></td>
                                                            <td>
                                                                <i class="fas fa-table text-primary mr-2"></i>
                                                                <strong><?php echo htmlspecialchars($tName); ?></strong>
                                                                <?php if (!empty($tbl['TABLE_COMMENT'])): ?>
                                                                    <span class="text-muted small ml-1">(<?php echo htmlspecialchars($tbl['TABLE_COMMENT']); ?>)</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <span id="badge-<?php echo htmlspecialchars($tName); ?>" class="<?php echo $isInnoDB ? 'badge-innodb' : (strtoupper($engine) === 'MYISAM' ? 'badge-myisam' : 'badge-other'); ?>">
                                                                    <i class="<?php echo $isInnoDB ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'; ?>"></i> <?php echo htmlspecialchars($engine); ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-right font-weight-bold"><?php echo number_format($rows); ?></td>
                                                            <td class="text-right text-muted small"><?php echo formatBytes($dataLen); ?></td>
                                                            <td class="text-right text-muted small"><?php echo formatBytes($indexLen); ?></td>
                                                            <td class="text-right font-weight-bold" id="size-<?php echo htmlspecialchars($tName); ?>"><?php echo formatBytes($totalLen); ?></td>
                                                            <td><span class="small text-muted"><?php echo htmlspecialchars($collation); ?></span></td>
                                                            <td class="text-center">
                                                                <button type="button" 
                                                                        class="btn btn-sm <?php echo $isInnoDB ? 'btn-outline-primary' : 'btn-warning font-weight-bold'; ?> btn-single-optimize"
                                                                        data-table="<?php echo htmlspecialchars($tName); ?>">
                                                                    <i class="fas fa-sync-alt"></i> <?php echo $isInnoDB ? 'Reindex' : 'แปลงเป็น InnoDB'; ?>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Information Guide Card -->
                                    <div class="card bg-light border-left-primary">
                                        <div class="card-body py-3">
                                            <h6 class="font-weight-bold text-primary mb-2">
                                                <i class="fas fa-info-circle mr-1"></i> ประโยชน์ของการใช้ InnoDB Engine และ Reindex
                                            </h6>
                                            <ul class="small text-muted mb-0 pl-3">
                                                <li><strong>InnoDB</strong> รองรับ Row-level Locking (ไม่ล็อกทั้งตารางเวลาบันทึกข้อมูล), Transactions (ACID) และ Crash Recovery ป้องกันข้อมูลเสียหาย</li>
                                                <li>การรัน <code>ALTER TABLE `table` ENGINE = InnoDB</code> จะทำการจัดเรียงโครงสร้างข้อมูลใหม่และสร้าง Index ใหม่ทั้งหมด</li>
                                                <li>คำสั่ง <code>ANALYZE TABLE</code> อัปเดตสถิติ Key Distribution เพื่อให้ MySQL Query Optimizer เลือก Index ได้เร็วและแม่นยำที่สุด</li>
                                                <li>สามารถสั่งรันผ่าน Command Line / Cronjob ได้โดยตรงด้วยคำสั่ง: <code>php reindex_table.php</code></li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php
            include('includes/Modal-Logout.php');
            include('includes/Footer.php');
            ?>
        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/myadmin.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tables        = <?php echo json_encode($tableNames); ?>;
            const startBtn      = document.getElementById('start-btn');
            const indexBtn      = document.getElementById('index-btn');
            const resetBtn      = document.getElementById('reset-btn');
            const downloadBtn   = document.getElementById('download-btn');
            const afterActionBtns = document.getElementById('after-action-btns');
            const progressBar   = document.getElementById('progress-bar');
            const uiSection     = document.getElementById('ui-section');
            const logWindow     = document.getElementById('log-window');
            const statusText    = document.getElementById('status-text');
            const countText     = document.getElementById('count-text');
            const totalSavedLabel = document.getElementById('total-saved');
            const lockOverlay   = document.getElementById('lock-overlay');
            const sidebar       = document.getElementById('accordionSidebar');

            let logContent = "";
            let totalSaved = 0;

            function setInterfaceLock(isLocked) {
                if (lockOverlay) lockOverlay.style.display = isLocked ? 'block' : 'none';
                if (sidebar) sidebar.classList.toggle('working-overlay', isLocked);
                if (startBtn) startBtn.disabled = isLocked;
                if (indexBtn) indexBtn.disabled = isLocked;
                document.querySelectorAll('.btn-single-optimize').forEach(btn => btn.disabled = isLocked);
            }

            function appendLog(message, color = '#dcdccc', isSkipped = false) {
                const time    = new Date().toLocaleTimeString();
                const logLine = `[${time}] ${message}`;
                const div     = document.createElement('div');
                div.style.color        = color;
                div.style.marginBottom = '4px';
                div.style.opacity      = isSkipped ? '0.5' : '1';
                div.innerText          = logLine;
                logWindow.appendChild(div);
                logWindow.scrollTop    = logWindow.scrollHeight;
                logContent            += logLine + "\n";
            }

            // Single table optimize button handler
            document.querySelectorAll('.btn-single-optimize').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const table = this.getAttribute('data-table');
                    if (!confirm(`ยืนยันการแปลง/Reindex ตาราง \`${table}\` เป็น InnoDB?`)) return;

                    setInterfaceLock(true);
                    uiSection.classList.remove('d-none');
                    appendLog(`กำลังจัดการตาราง \`${table}\`...`, '#8be9fd');
                    statusText.innerText = `กำลังจัดการ: ${table}...`;

                    try {
                        const res = await fetch(`?action=optimize&table=${encodeURIComponent(table)}`);
                        const result = await res.json();

                        if (result.success) {
                            appendLog(result.message, '#8cf68c');
                            // Update badge
                            const badge = document.getElementById(`badge-${table}`);
                            if (badge) {
                                badge.className = 'badge-innodb';
                                badge.innerHTML = '<i class="fas fa-check-circle"></i> InnoDB';
                            }
                            this.className = 'btn btn-sm btn-outline-primary btn-single-optimize';
                            this.innerHTML = '<i class="fas fa-sync-alt"></i> Reindex';
                        } else {
                            appendLog(result.message, '#ff6b6b');
                        }
                    } catch (err) {
                        appendLog(`❌ เกิดข้อผิดพลาดในการประมวลผลตาราง: ${table}`, '#ff6b6b');
                    }

                    statusText.innerText = "ดำเนินการเสร็จสิ้น";
                    setInterfaceLock(false);
                });
            });

            // Start All Tables Optimize & Convert
            startBtn.addEventListener('click', async () => {
                if (!confirm('ยืนยันการเริ่มทำงาน? ระบบจะทำการแปลง Engine เป็น InnoDB และ Reindex ทุกตาราง')) return;

                setInterfaceLock(true);
                afterActionBtns.classList.add('d-none');
                startBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังดำเนินการ...';
                uiSection.classList.remove('d-none');
                logWindow.innerHTML = '';
                totalSaved = 0;
                totalSavedLabel.innerText = "0.00";
                logContent = "SAC Event - Database Optimization & InnoDB Converter Report\nDate: " + new Date().toLocaleString() + "\n" + "=".repeat(60) + "\n";

                let completed = 0;
                const total = tables.length;

                for (const table of tables) {
                    statusText.innerText = `กำลังจัดการ: ${table}... (${completed + 1}/${total})`;
                    try {
                        const res = await fetch(`?action=optimize&table=${encodeURIComponent(table)}`);
                        const result = await res.json();

                        if (result.skipped) {
                            appendLog(result.message, '#888888', true);
                        } else if (result.success) {
                            const saved = Math.max(0, result.before - result.after);
                            totalSaved += saved;
                            totalSavedLabel.innerText = totalSaved.toFixed(2);
                            appendLog(result.message, '#8cf68c');

                            // Update badge in table row
                            const badge = document.getElementById(`badge-${table}`);
                            if (badge) {
                                badge.className = 'badge-innodb';
                                badge.innerHTML = '<i class="fas fa-check-circle"></i> InnoDB';
                            }
                            const singleBtn = document.querySelector(`[data-table="${table}"]`);
                            if (singleBtn) {
                                singleBtn.className = 'btn btn-sm btn-outline-primary btn-single-optimize';
                                singleBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Reindex';
                            }
                        } else {
                            appendLog(result.message, '#ff6b6b');
                        }

                    } catch (error) {
                        appendLog(`❌ ไม่สามารถประมวลผลตาราง: ${table}`, '#ff6b6b');
                    }

                    completed++;
                    const percent = Math.round((completed / total) * 100);
                    progressBar.style.width = percent + '%';
                    progressBar.innerText = percent + '%';
                    countText.innerText = `${completed} / ${total}`;
                }

                logContent += "=".repeat(60) + "\nTotal Space Saved: " + totalSaved.toFixed(2) + " MB\n";
                statusText.innerText = "✅ เสร็จสมบูรณ์ทุกตาราง!";
                startBtn.classList.add('d-none');
                if (indexBtn) indexBtn.classList.add('d-none');
                afterActionBtns.classList.remove('d-none');
                setInterfaceLock(false);
            });

            // Index Optimization Button
            if (indexBtn) {
                indexBtn.addEventListener('click', async () => {
                    if (!confirm('ยืนยันการสร้าง/ปรับปรุง Indexes แนะนำสำหรับตารางสำคัญ?')) return;

                    setInterfaceLock(true);
                    afterActionBtns.classList.add('d-none');
                    indexBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังตรวจสอบและสร้าง Index...';
                    uiSection.classList.remove('d-none');
                    logWindow.innerHTML = '';
                    logContent = "SAC Event - Database Index Optimization Report\nDate: " + new Date().toLocaleString() + "\n" + "=".repeat(60) + "\n";

                    progressBar.style.width = '50%';
                    progressBar.innerText = '50%';
                    statusText.innerText = "กำลังสร้าง/ปรับปรุงดัชนี...";

                    try {
                        const res = await fetch('?action=apply_indexes');
                        const result = await res.json();

                        if (result.success) {
                            result.messages.forEach(msg => {
                                appendLog(msg, '#8cf68c');
                            });
                            statusText.innerText = "✅ ดำเนินการปรับปรุง Index เสร็จสมบูรณ์!";
                        } else {
                            result.messages.forEach(msg => {
                                appendLog(msg, '#ff6b6b');
                            });
                            statusText.innerText = "❌ เกิดข้อผิดพลาดในการสร้าง Index";
                        }
                    } catch (error) {
                        appendLog("❌ ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ในการจัดการ Index ได้", '#ff6b6b');
                        statusText.innerText = "❌ เกิดข้อผิดพลาด";
                    }

                    progressBar.style.width = '100%';
                    progressBar.innerText = '100%';
                    indexBtn.innerHTML = '<i class="fas fa-key mr-2"></i>สร้าง/ปรับปรุง Index เพิ่มประสิทธิภาพ';
                    startBtn.classList.add('d-none');
                    indexBtn.classList.add('d-none');
                    afterActionBtns.classList.remove('d-none');
                    setInterfaceLock(false);
                });
            }

            // Reset UI Button
            resetBtn.addEventListener('click', () => {
                startBtn.classList.remove('d-none');
                if (indexBtn) indexBtn.classList.remove('d-none');
                startBtn.innerHTML = '<i class="fas fa-play mr-2"></i>เริ่มรัน Optimize & ปรับ InnoDB ทุกตาราง';
                afterActionBtns.classList.add('d-none');
                uiSection.classList.add('d-none');
                totalSavedLabel.innerText = "0.00";
                progressBar.style.width = '0%';
                progressBar.innerText = '0%';
                logWindow.innerHTML = '<div style="color: #666;">--- กดปุ่มด้านบนเพื่อเริ่มกระบวนการ ---</div>';
            });

            // Download Report Log Button
            downloadBtn.addEventListener('click', () => {
                const blob = new Blob([logContent], { type: 'text/plain;charset=utf-8' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `db_reindex_report_${new Date().toISOString().slice(0,10)}.txt`;
                a.click();
                window.URL.revokeObjectURL(url);
            });
        });
    </script>
    </body>
    </html>
<?php } ?>
