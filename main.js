
$(document).ready(function () {
  $('#formCheck').on("change", hideBlock);
  $('#formDate').datepicker({
    minDate: new Date()
  });
  $('#formBtn').on("click", function (e){
    
    let startDate = $("#formDate").val().trim();
    let term = $("#formDuration").val().trim();
    let selectDuration = $('#select option:selected').text();
    let sum = $("#formDeposit").val().trim();
    let percent = $("#formPercent").val().trim();
    let sumAdd = $("#formRefill").val().trim();
    document.getElementById('form__line').style.opacity = '1';
    if (startDate == "") {
      $("#errorMess").text("Введите дату открытия");
      return false;
    } else if (term == "") {
      $("#errorMess").text("Укажите срок вклада");
      return false;
    } else if (sum == "") {
      $("#errorMess").text("Введите сумму вклада");
      return false;
    } else if (percent == "") {
      $("#errorMess").text("Введите процентную ставку");
      return false;
    } else if (sumAdd == "") {
      $("#errorMess").text("Введите сумму пополнения счёта или оставьте 0");
      return false;
    }
    $("#errorMess").text("");
    e.preventDefault();
    
    $.ajax({
      type: "POST",
      url: "calc.php",
      data: { 'startDate': startDate, 'term': term, 'selectDuration': selectDuration,'sum': sum, 'percent': percent, 'sumAdd': sumAdd},
      dataType: "html",
      beforeSend: function() {
        $('#formBtn').prop("disabled", true);
      },
      success: function (data) {
        $("#errorMess").addClass("form__action");
        $("#errorMess").text(data);
        $('#formBtn').prop("disabled", false);
      }
    });
    
});

  function hideBlock() {
    if ($(this).is(':checked')) {
      document.getElementById("formRefill").value = '0';
      document.getElementById("hideThis").style.opacity = '1';
    }
    else {
      document.getElementById("formRefill").value = '0';
      document.getElementById("hideThis").style.opacity = '0';
    }
  };
  
});

