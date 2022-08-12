
 ( function() {
    var options = {
      whatsapp: "+2348129577627", // WhatsApp number
      sms: "+2348129577627", // Sms phone number
      call_to_action: "Instant Chat with us at FPP Schools", // Call to action
      button_color: "#FF318E", // Color of button
      position: "right", // Position may be 'right' or 'left'
      order: "whatsapp,sms", // Order of buttons
    };
    var proto = document.location.protocol,
      host = "whatshelp.io",
      url = proto + "//static." + host;
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = url + '/widget-send-button/js/init.js';
    s.onload = function() {
      WhWidgetSendButton.init(host, proto, options);
    };
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
  })();