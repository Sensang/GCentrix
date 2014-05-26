<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,700' rel='stylesheet' type='text/css'>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script>
            <?php
            $NumberOfInitSteps = 4;
            $NumberOfInstallSteps = 3;
            echo "var NumberOfInitSteps = $NumberOfInitSteps;";
            echo "var NumberOfInstallSteps = $NumberOfInstallSteps";
            ?>
        </script>
        <script src="install.js"></script>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="icon" type="image/png" href="favicon.png">
        <title>
            Installation
        </title>
    </head>
    <body>
        <div id="Content">
            <div id="Step1" class="Step">
                <div class="SubStep Headline">
                    Welcome to the <span class="Highlight Blue">GCentrix</span> Installation Assistant
                </div>
                <div class="SubStep">
                    Press Start in order to initiate the installation process
                </div>
            </div>
            <div id="Step2" class="Step" hidden>
                <div class="SubStep Headline">
                    Please enter the database to use with <span class="Highlight Blue">GCentrix</span>
                </div>
                <div class="SubStep">
                    <label class="Label">
                        Server Address
                    </label>
                    <input type="text" name="Server" value="" />
                </div>
                <div class="SubStep">
                    <label class="Label">
                        Database Port
                    </label>
                    <input type="text" name="Port" value="" />
                </div>
                <div class="SubStep Headline">
                    <label class="Label">
                        Database Name
                    </label>
                    <input type="text" name="Database" value="" />
                </div>
                <div class="SubStep">
                    please make sure you have <span class="Highlight Blue">PostgreSQL</span> up and running. For the installation it is necessary for you to have <span class="Highlight Red">one empty database</span> to use exclusively for the <span class="Highlight Blue">GCentrix</span> Database Manager
                </div>
            </div>
            <div id="Step3" class="Step" hidden>
                <div class="SubStep Headline">
                    Please enter name and password of the <span class="Highlight Blue">PostgreSQL</span> user
                </div>
                <div class="SubStep">
                    <label class="Label">
                        User Name
                    </label>
                    <input type="text" name="User" value="" />
                </div>
                <div class="SubStep">
                    <label class="Label">
                        User Password
                    </label>
                    <input type="password" name="Password" value="" />
                </div>
                <div class="SubStep"></div>
                <div class="SubStep">
                    Keep in mind that this <span class="Highlight Red">user has to have most privileges</span> in the specified database, because all <span class="Highlight Blue">GCentrix</span> Queries will be sent through this user
                </div>
            </div>
            <div id="Step4" class="Step" hidden>
                <div id="Step4" class="SubStep Headline">
                    When you press finish <span class="Highlight Blue">GCentrix</span> will try to create the necessary demo data for your database and create cache files for performance reasons. This should take less than a minute
                </div>
            </div>
            <div id="ControlArea" action="data/" method="post">
                <button id="Control" type="Button" id="Previous" class="GreyedOut" disabled>Previous</button>
                <button id="Control" type="Button" id="Next">Start</button>
            </div>
            <div id="ProgressArea">
                <?php
                echo "<button class=\"ProgressBar Active\" id=\"ProgressBar1\">Step 1</div>";
                for($Counter = 2; $Counter < ($NumberOfInitSteps + 1); $Counter++) {
                    echo "<button class=\"ProgressBar Todo\" id=\"ProgressBar$Counter\">Step $Counter</div>";
                }
                ?>
            </div>
        </div>
    </body>
</html>
