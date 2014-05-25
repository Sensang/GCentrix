<html>
    <head>
        <meta charset="UTF-8">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!--<script src="install/jquery.steps.js"></script>-->
        <script src="install/install.js"></script>
        <link rel="stylesheet" type="text/css" href="install/style.css">
        <link rel="icon" type="image/png" href="favicon.png">
        <title>
            Installation
        </title>
    </head>
    <body>
        <div id="Content">
            <div id="Step1" class="Step">
                Welcome to the GCentrix Installation Assistant.
                <br />
                Please press Start to continue.
            </div>
            <div id="Step2" class="Step" hidden>
                <div class="SubStep">
                    Please enter your Database information:
                </div>
                <div class="SubStep">
                    <label class="Label">
                        Server Address
                    </label>
                    <input form="ControlArea" type="text" name="Server" value="" />
                </div>
                <div class="SubStep">
                    <label class="Label">
                        Database Port
                    </label>
                    <input form="ControlArea" type="text" name="Port" value="" />
                </div>
                <div class="SubStep">
                    <label class="Label">
                        Database Name
                    </label>
                    <input form="ControlArea" type="text" name="Database" value="" />
                </div>
                <div class="SubStep">
                    <label class="Label">
                        User Name
                    </label>
                    <input form="ControlArea" type="text" name="User" value="" />
                </div>
                <div class="SubStep">
                    <label class="Label">
                        User Password
                    </label>
                    <input form="ControlArea" type="password" name="Password" value="" />
                </div>
            </div>
            <div id="Step3" class="Step" hidden>
                When you press finish GCentrix will try to create the necessary Demo data for your database
            </div>
            <form id="ControlArea" action="install/data.php" method="get">
                <button draggable="true" type="Button" id="Previous" class="GreyedOut" disabled>Previous</button>
                <button type="Button" id="Next">Start</button>
            </form>
        </div>
    </body>
</html>
