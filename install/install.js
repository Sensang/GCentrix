var Steps = 1;
$(document).ready(function(){
    var NumberOfSteps = 3;
    $("#Next").click(function(){
        if (Steps === NumberOfSteps) {
            $("#ControlArea").submit();
        } else {
            $("#Step" + Steps).hide();
            Steps++;
            $("#Step" + Steps).show();
            
            if (Steps === 2) {
                $("#Previous").addClass("Lighted");
                $("#Previous").removeClass("GreyedOut");
                $("#Previous").prop("disabled",false);
                $("#Next").html("Next");
            }
            if (Steps === NumberOfSteps) {
                $("#Next").html("Finish");
            }
        }
    });
    $("#Previous").click(function(){
        if (Steps > 1) {
            $("#Step" + Steps).hide();
            Steps--;
            $("#Step" + Steps).show();
            if (Steps === 1) {
                $("#Previous").removeClass("Lighted");
                $("#Previous").addClass("GreyedOut");
                $("#Previous").prop("disabled",true);
                $("#Next").html("Start");
            }
            if (Steps === (NumberOfSteps - 1)) {
                $("#Next").html("Next");
            }
        }
  });
});