function initCarCalendar() {
  new Calendar("car-calendar", {
    url: WEB_URL + "index.php/car/model/calendar/toJSON",
    onclick: function(d) {
      send(
        WEB_URL + "index.php/car/model/index/action",
        "action=detail&id=" + this.id,
        doFormSubmit
      );
    }
  });
  forEach($E('car_links').getElementsByTagName('a'), function() {
    callClick(this, function() {
      send(
        WEB_URL + "index.php/car/model/vehicles/action",
        'action=detail&id=' + this.id.replace('car_', ''),
        doFormSubmit,
        this
      );
    });
  });
}