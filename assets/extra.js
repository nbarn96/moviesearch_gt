// Search function in AJAX.
$(document).ready(function() {
  $("#loading").hide();

  var query = document.getElementById("query");
  var ret_results = document.getElementById("results");

  $("#query").change(function() {
    var value = this.value;
    var user_role = $("#role").val();
    if (value == "") {
      $(".help").show();
      $("#loading").hide();
      $("#results").hide();
    } else {
      $(".help").hide();
      $("#results").hide();
      $("#loading").show();
      $.ajax({
        type: 'POST',
        url: 'assets/scripts/search.php',
        data: { 'action': value, 'role': user_role },
        success: function(html) {
          $("#loading").hide();
          $(".help").hide();
          $("#results").html(html).show();
        }
      });
    }
  });
});
