var Steps = 1;
var AllowedChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_ ";
$(document).ready(function(){
    function SetSteps(NewSteps) {
        // for (Counter = 0; Steps)
        $("#Step" + Steps).hide();
        $("#ProgressBar" + Steps).addClass("Done");
        $("#ProgressBar" + Steps).removeClass("Active");
        Steps = NewSteps;
        $("#Step" + Steps).show();
        $("#ProgressBar" + Steps).addClass("Active");
    }
    $("#Next").click(function(){
        if (Steps === NumberOfInitSteps) {
            $.get("install.php?Server="   + $('input[name="Server"]').value +
                                "&Port="     + $('input[name="Port"]').value +
                                "&Database=" + $('input[name="Database"]').value +
                                "&User="     + $('input[name="User"]').value +
                                "&Password=" + $('input[name="Password"]').value,
                                function(data,status) {
                                    alert("Data: " + data + "\nStatus: " + status);                                    
                                });
        } else {
            Steps = NewSteps;
            $("#Step" + Steps).hide();
            $("#ProgressBar" + Steps).addClass("Done");
            $("#ProgressBar" + Steps).removeClass("Active");
            Steps++;
            $("#Step" + Steps).show();
            $("#ProgressBar" + Steps).addClass("Active");
            
            if (Steps === 2) {
                $("#Previous").addClass("Lighted");
                $("#Previous").removeClass("GreyedOut");
                $("#Previous").prop("disabled",false);
                $("#Next").html("Next");
            }
            if (Steps === NumberOfInitSteps) {
                $("#Next").html("Finish");
            }
        }
    });
    $("#Previous").click(function(){
        if (Steps > 1) {
            $("#Step" + Steps).hide();
            $("#ProgressBar" + Steps).addClass("Todo");
            $("#ProgressBar" + Steps).removeClass("Active");
            Steps--;
            $("#Step" + Steps).show();
            $("#ProgressBar" + Steps).removeClass("Done");
            $("#ProgressBar" + Steps).addClass("Active");
            if (Steps === 1) {
                $("#Previous").removeClass("Lighted");
                $("#Previous").addClass("GreyedOut");
                $("#Previous").prop("disabled",true);
                $("#Next").html("Start");
            }
            if (Steps === (NumberOfInitSteps - 1)) {
                $("#Next").html("Next");
            }
        }
    });
    $('input.').change(function() {
        String = $(this).text();
        for (Counter = 0; Counter < String.length; Counter++) {
            if (jQuery.inArray(String[Counter], AllowedChars)) {
            $(this).addClass("RedInput");
            }
        }
    });
});