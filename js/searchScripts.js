//Getting value from "search.php".
function fill(Value) {
  //Assigning value to "search" div in mainFeed.php file.
  $('#search').val(Value);
  //Hiding "display" div in mainFeed.php file.
  $('#display').hide();
}

$(document).ready(function() {
  //On pressing a key on "Search box" in "search.php" file. This function will be called.
  $("#search").keyup(function(e) {
    //Assigning search box value to javascript variable named as "name".
    var name = $('#search').val();
    //Validating, if "name" is empty.
    if (name == "") {
      $("#display").html("");
    } else {
      $.ajax({
        //AJAX type is "Post".
        type: "POST",
        //Data will be sent to "search.php".
        url: "phpconnect.php",
        //Data, that will be sent to "search.php".
        data: {
          search: name
        },
        success: function(html) {
          $("#display").html(html).show();
        }
      });
    }
  });
});
