<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>jQuery Alertify Plugin</title>
    <link rel="stylesheet" href="alertify/css/alertify.css" />
    <link rel="stylesheet" href="alertify/css/alertify.rtl.css" id="linkID" />

    <script src="http://code.jquery.com/jquery-1.9.1.js">
    </script>

    <script src="alertify/alertify.js"></script>

    <style>
        .alertify-log-custom {
            background: green;
        }

        .height {
            height: 10px;
        }
    </style>
</head>

<body>
<h1 style="color:green">GeeksforGeeks</h1>
<b> jQuery Alertify Plugin</b>
<div class="height"></div><br>

<b>Alertify Dialogs</b>
<div class="height"></div>
<a href="#" id="alertID">Click Alert Dialog</a><br>
<a href="#" id="promptID">Click Prompt Dialog</a><br>
<a href="#" id="confirmID">Click Confirm Dialog</a><br>
<a href="#" id="focusID">Click Button Focus</a><br>
<a href="#" id="labelsID">Click Custom Labels</a><br>
<a href="#" id="orderID">Click Button Order</a>


<script>
    function reset() {
        $("#linkID").attr("href", "alertify.default.css");
        alertify.set({
            labels: {
                ok: "OK",
                cancel: "Cancel"
            },
            delay: 4000,
            buttonFocus: "ok",
            buttonReverse: false

        });
    }

    // Alertify Standard Dialog boxes
    $("#alertID").on('click', function () {
        reset();
        alertify.alert("Welcome GFG !");
        alertify.alert("Alertify alert dialog");
        return false;
    });

    $("#confirmID").on('click', function () {
        reset();
        alertify.confirm(
            "Please confirm the dialog box ", function (event) {
                if (event) {
                    alertify.success(
                        "You have clicked OK to confirm our"
                        + " terms and conditions.");
                } else {
                    alertify.error(
                        "You have clicked Cancel not to confirm.");
                }
            });
        return false;
    });

    $("#promptID").on('click', function () {
        reset();
        alertify.prompt(
            "This is a prompt dialog box", function (event, string) {
                if (event) {
                    alertify.success(
                        "You have clicked OK and typed: " + string);
                } else {
                    alertify.error(
                        "You have clicked Cancel");
                }
            }, "Please enter, this is default value");
        return false;
    });


    $("#success").on('click', function () {
        reset();
        alertify.success("Success message");
        return false;
    });

    $("#error").on('click', function () {
        reset();
        alertify.error("Error message");
        return false;
    });


    $("#labelsID").on('click', function () {
        reset();
        alertify.set({ labels: { ok: "Accept", cancel: "Deny" } });
        alertify.confirm(
            "Confirm dialog with custom button labels",
            function (event) {
                if (event) {
                    alertify.success("You have clicked OK");
                } else {
                    alertify.error("You have clicked Cancel");
                }
            });
        return false;
    });

    $("#focusID").on('click', function () {
        reset();
        alertify.set({ buttonFocus: "cancel" });
        alertify.confirm(
            "Confirm dialog with cancel button focused",
            function (event) {
                if (event) {
                    alertify.success("You have clicked OK");
                } else {
                    alertify.error("You have clicked Cancel");
                }
            });
        return false;
    });

    $("#orderID").on('click', function () {
        reset();
        alertify.set({ buttonReverse: true });
        alertify.confirm(
            "Confirm dialog with reversed button order",
            function (event) {
                if (event) {
                    alertify.success("You have clicked OK");
                } else {
                    alertify.error("You have clicked Cancel");
                }
            });
        return false;
    });
</script>
</body>

</html>

