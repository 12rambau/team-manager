function updateTime()
{
	var now = moment();
	
    if (this.timer)
        clearTimeout(this.timer);

    $("#first-line").css('height', now.hour()*60+now.minute()); 
  
    this.timer = setTimeout('updateTime()', 60*1000);
}

updateTime();