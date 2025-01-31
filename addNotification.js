function addNotification() {
    var notification = $(".bell-notification");
  
    if (!$(notification).hasClass("new-not")) {
      $(".bell-top").addClass("bell-top-anim");
      $(".bell-bot").addClass("bell-bot-anim");
  
      setTimeout(function () {
        $(notification).addClass("new-not");
      }, 800);
  
      $(notification).html(parseInt($(notification).html(), 10) + 1);
    } else {
      $(".bell-top").removeClass("bell-top-anim");
      $(".bell-bot").removeClass("bell-bot-anim");
      $(notification).removeClass("new-not");
    }
  }
  