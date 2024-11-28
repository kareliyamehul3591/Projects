jQuery("#main-banner").owlCarousel({
    autoplay: true,
    rewind: true,
    margin: 0,
    loop: true,
    dots: true,
    responsiveClass: true,
    autoHeight: true,
    autoplayTimeout: 7000,
    smartSpeed: 800,
    nav: false,
    navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
    responsive: {
      0: {
        items: 1
      },
  
      320: {
        items: 1
      },
  
      991: {
        items: 1
      },
      1024: {
        items: 1
      }
    }
  });
  jQuery("#stories-slider").owlCarousel({
    autoplay: true,
    rewind: true,
    margin: 20,
    animateOut: 'fadeOut',
    animateIn: 'fadeIn',
    loop: true,
    dots: true,
    responsiveClass: true,
    autoHeight: true,
    autoplayTimeout: 7000,
    smartSpeed: 800,
    nav: true,
    navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
    responsive: {
      0: {
        items: 1
      },
  
      525: {
        items: 2
      },
      768: {
        items: 2
      },
      991: {
        items: 3
      },
      1024: {
        items: 3
      }
    }
  });
  jQuery("#clients-slider").owlCarousel({
    autoplay: true,
    rewind: true,
    margin: 20,
    animateOut: 'fadeOut',
    animateIn: 'fadeIn',
    loop: true,
    dots: true,
    responsiveClass: true,
    autoHeight: true,
    autoplayTimeout: 7000,
    smartSpeed: 800,
    nav: true,
    navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
    responsive: {
      
      0: {
        items: 1
      },
  
      480: {
        items: 2
      },
  
      575: {
        items: 3
      },
      768: {
        items: 4
      },
      991: {
        items: 3
      },
      1024: {
        items: 5
      }
    }
  });

  
  

// $('select').each(function () {
//     var $this = $(this), numberOfOptions = $(this).children('option').length;

//     $this.addClass('select-hidden');
//     $this.wrap('<div class="select"></div>');
//     $this.after('<div class="select-styled"></div>');

//     var $styledSelect = $this.next('div.select-styled');
//     $styledSelect.text($this.children('option').eq(0).text());

//     var $list = $('<ul />', {
//       'class': 'select-options'
//     }).insertAfter($styledSelect);

//     for (var i = 0; i < numberOfOptions; i++) {
//       $('<li />', {
//         text: $this.children('option').eq(i).text(),
//         rel: $this.children('option').eq(i).val()
//       }).appendTo($list);
//     }

//     var $listItems = $list.children('li');

//     $styledSelect.click(function (e) {
//       e.stopPropagation();
//       $('div.select-styled.active').not(this).each(function () {
//         $(this).removeClass('active').next('ul.select-options').hide();
//       });
//       $(this).toggleClass('active').next('ul.select-options').toggle();
//     });

//     $listItems.click(function (e) {
//       e.stopPropagation();
//       $styledSelect.text($(this).text()).removeClass('active');
//       $this.val($(this).attr('rel'));
//       $list.hide();
//       //console.log($this.val());
//     });

//     $(document).click(function () {
//       $styledSelect.removeClass('active');
//       $list.hide();
//     });

//   });




  var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
$('.navbar-nav a').each(function() {
 if (this.href === path) {
  $(this).addClass('active');
 }
});
var dash_path = window.location.href; // because the 'href' property of the DOM element is the absolute path
$('.nav-box a').each(function() {
 if (this.href === dash_path) {
  $(this).parents('li').addClass('active');
 }
});
$(".custom-accordion .btn").click(function(){
  $(".custom-accordion .card").removeClass("active");
  if( $(this).hasClass("collapsed") == true ){
    $(this).parents(".card").addClass("active");
  }
})

$(".fa.fa-eye.input-icon").click(function() {
  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $(this).siblings("input");
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});


$(".custom-dropdown-select .dropdown-item").click(function(){
   var valueselected = $(this).text();
   $(this).parents(".custom-dropdown-select").find(".value-assign").text(valueselected);
})


$(".therapy_box .top .btn").click(function(){
  if( $(this).hasClass("collapsed") == true ) {
    $(".therapy_box").removeClass("active");
    $(this).parents(".therapy_box").addClass("active");
  }
  else {
    $(this).parents(".therapy_box").removeClass("active");
  }
})

$(".session-dates a").click(function(){
  $(this).siblings("a").removeClass("active");
  $(this).addClass("active");
})
$(".time-boxes .time-period").click(function(){
  $(this).siblings(".time-period").removeClass("active");
  $(this).addClass("active");
})


$('.dropdown-menu.click-open').on('click', function(event){
  // The event won't be propagated up to the document NODE and 
  // therefore delegated events won't be fired
  event.stopPropagation();
});





 



$('#hamburger').click(function() {
  $( this ).toggleClass( "active" );
  $(".dashboard-menu, .dashboard-right").toggleClass("dashboard-toggle");
});