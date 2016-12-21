/*--
    File	: js/jqry.js
    Author	: Abhishek Nath
    Date	: 01-Jan-2015
    Desc	: JQuery related functions.
--*/

/*--
    01-Jan-15   V1-01-00   abhishek   $$1   Created.
    17-Jul-15   V1-01-00   abhishek   $$2   File header comment added.
--*/


$(function(){
  // Helper function to convert a string of the form "Mar 15, 1987" into
  // a Date object.
    var date_from_string = function(str) {
    var months = ["jan","feb","mar","apr","may","jun","jul",
      "aug","sep","oct","nov","dec"];
    var pattern = "^([a-zA-Z]{3})\\s*(\\d{2}),\\s*(\\d{4})$";
    var re = new RegExp(pattern);
    var DateParts = re.exec(str).slice(1);

    var Year = DateParts[2];
    var Month = $.inArray(DateParts[0].toLowerCase(), months);
    var Day = DateParts[1];
    return new Date(Year, Month, Day);
  }

  var moveBlanks = function(a, b) {
    if ( a < b ){
      if (a == "")
    return 1;
      else
    return -1;
    }
    if ( a > b ){
      if (b == "")
    return -1;
      else
    return 1;
    }
    return 0;
  };

  var moveBlanksDesc = function(a, b) {
    // Blanks are by definition the smallest value, so we don't have to
    // worry about them here
    if ( a < b )
      return 1;
    if ( a > b )
      return -1;
    return 0;
  };

  var table = $("table").stupidtable({
    "date":function(a,b){
      // Get these into date objects for comparison.

      aDate = date_from_string(a);
      bDate = date_from_string(b);

      return aDate - bDate;
    },
    "moveBlanks": moveBlanks,
    "moveBlanksDesc": moveBlanksDesc,
  });

  table.on("aftertablesort", function (event, data) {
    var th = $(this).find("th");
    th.find(".arrow").remove();
    var dir = $.fn.stupidtable.dir;

    var arrow = data.direction === dir.ASC ? "&uarr;" : "&darr;";
    th.eq(data.column).append('<span class="arrow">' + arrow +'</span>');

    var th_f = $("#fixed_table").find("th");
    th_f.find(".arrow").remove();
    th_f.eq(data.column).append('<span class="arrow">' + arrow +'</span>');
    for ( var i = 0; i < th_f.length; i++ ) {
    th_f.eq(i).css("width",($(this).find("th").eq(i).outerWidth())+"px");
    }
  });

  $("tr").slice(1).click(function(){
    $(".awesome").removeClass("awesome");
    $(this).addClass("awesome");
  });


  $.fn.fixMe = function() {
      return this.each(function() {
     var $this = $(this),
    $t_fixed;
     function init() {
    $this.wrap('<div class="container" />');
    $t_fixed = $this.clone();
    $t_fixed.attr('id', 'fixed_table');
    $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
    resizeFixed();
     }
     function resizeFixed() {
    $t_fixed.find("th").each(function(index) {
       $(this).css("width",($this.find("th").eq(index).outerWidth() - 2)+"px");
    });
     }
     function scrollFixed() {
    var offset = $(this).scrollTop(),
    tableOffsetTop = $this.offset().top,
    tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
    if(offset < tableOffsetTop || offset > tableOffsetBottom)
       $t_fixed.hide();
    else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
       $t_fixed.show();
     }
     $(window).resize(resizeFixed);
     $(window).scroll(scrollFixed);
     init();
      });
   };

});
