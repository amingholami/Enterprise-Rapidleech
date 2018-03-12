var idleTime = 0;
$(document).ready(function(){
  var idleInterval = setInterval("idleTime++;", 1000);
  $(this).mousemove(function(e){
    var tmp = idleTime;
    idleTime = 0;
    if (tmp >= 120) { stats_timer = setTimeout("refreshStats()",1000); }
  });
});

var stats_timed = 2;
function refreshStats() 
{
	$.ajax
	({
		type: "GET",
		url: 'ajax.php?ajax=server_stats',
		dataType: 'json',
		success: function (data) 
		{
			function rang_td_progress(percent){
			  if (percent >= 0  ) { className = 'raftez'; } 
			  if (percent > 19.8) { className = 'raftea'; } 
			  if (percent > 36.7) { className = 'rafteb'; }
			  if (percent > 64.6) { className = 'raftec'; }
			  if (percent > 80.3) { className = 'rafted'; }
			  return className;
			  }
			document.getElementById('cpupercent').className = rang_td_progress(data.CPUPercent);
			document.getElementById('progress').className   = rang_td_progress(data.InUsePercent);
//			document.getElementById('HSUP').className       = rang_td_progress(data.HSUP);
			$('#cpuload').html(data.CPULoad);
			$('#cpupercent_inner').html(data.CPUPercent);
			$('#cpupercent').css('width',data.CPUPercent+"%");
			$('#inuse').html(data.InUse);
			$('#inusepercent').html(data.InUsePercent);
			$('#freespace').html(data.FreeSpace);
			$('#diskspace').html(data.DiskSpace);
			$('#progress').css('width',data.InUsePercent+"%");
//			$('#HSUP').animate({width:data.HSUP+"%"},{queue:false,duration:10*(100-data.HSUP)});

			setTimeout("refreshStats()",stats_timed * 1000);
		}
	});
}