/* EYA - Easy YTS Adder.  Plex library aware YTS torrent download viewer with Transmission Integration
*	Copyright (C) 2014 	Jamie Briers 	<development@jrbriers.co.uk>
*						Chris Pomfret	<enquiries@chrispomfret.com>
*
*	This program is free software; you can redistribute it and/or modify
*	it under the terms of the GNU General Public License as published by
*	the Free Software Foundation; either version 2 of the License, or
*	(at your option) any later version.
*
*	This program is distributed in the hope that it will be useful,
*	but WITHOUT ANY WARRANTY; without even the implied warranty of
*	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*	GNU General Public License for more details.
*
*	You should have received a copy of the GNU General Public License along
*	with this program; if not, write to the Free Software Foundation, Inc.,
*	51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/
function getPercentage(xt,element){
	//console.log(xt);
	url = "core/getPercentage.php?xt="+xt;
	$.ajax({
		url: url,
		context: document.body
	}).done(function(data) {
		//console.log(data);
		obj = JSON.parse(data);
		//console.log($(element));
		switch(obj.status){
			case 0://stopped torrent
				element.children(".percentage").html("Stopped");
				element.children(".downloadingBar").css('height',obj.percentage + "%");
				break;
			case 3://queued to download
			case 1://queued to check files
				element.children(".percentage").html("Queued");
				element.children(".downloadingBar").css('height',obj.percentage + "%");
				break;
			case 2:
				element.children(".percentage").html("Checking Files");
				element.children(".downloadingBar").css('height',obj.percentage + "%");
				break;
			case 4://downloading
				element.children(".percentage").html(obj.percentage + "%");
				element.children(".downloadingBar").css('height',obj.percentage + "%");
				break;
			case 5://queued to seed
				element.children(".percentage").html("Queued to Seed");
				element.children(".downloadingBar").css('height',obj.percentage + "%");
				break;
			case 6:
				element.children(".percentage").html("Seeding");
				element.children(".downloadingBar").css('height',obj.percentage + "%");
				break;
			case -1:
				element.children(".percentage").html("Removed");
				element.children(".downloadingBar").css('height',"0%");
				image = element.children("img.box-art")
				image.removeClass("greyed");
				var dataMagnet = image.attr('data-magnet-backup');
		 		image.attr('data-magnet-backup',"");
		 		image.attr('data-magnet', dataMagnet);

		 		image.css('cursor',"pointer");

		 		var timer = image.attr('timer');
		 		clearInterval(timer);
		 		image.attr('timer','');

		 		//element.append('<div class="downloadingBar" style="height: 0%" ></div>');
		 		//element.append("<p class='percentage'>Adding</p>");
		 		element.removeClass("downloading");
		 		element.children("div").remove();
		 		element.children("p").remove();

				break;
			default:
				element.children(".percentage").html("Unknown status");
				element.children(".downloadingBar").css('height',obj.percentage + "%");
				break;
		}

	});
}
$(document).ready(function(){
	$("img[data-magnet]").click(function(){
		image = $(this);
		//console.log("clicked image!!");
		magnet = $(this).attr('data-magnet');
		url = "core/download.php?magnetLink="+magnet;
		//console.log(url);
		$.ajax({
			url: url ,
			context: document.body
		}).done(function(data) {
			//console.log(data);
			if(data == "OK"){
			 		image.addClass("greyed");
			 		var dataMagnet = image.attr('data-magnet');
			 		image.attr('data-magnet',"");
			 		image.attr('data-magnet-backup', dataMagnet)
			 		image.css('cursor',"");
			 		//$(image).parent().unblock();

			 		element = $(image).parent();

			 		element.append('<div class="downloadingBar" style="height: 0%" ></div>');
			 		element.append("<p class='percentage'>Adding</p>");
			 		element.addClass("downloading");

				 	xt = $(element).parent().attr('data-xt');

				 	var interval = window.setInterval(function(xt, element){
						getPercentage(xt,element);
						//console.log("getPercentage " + xt);
					},200,xt,element);
					image.attr("timer",interval);
			 }else{
				var percentElement = element.append("<p class='percentage'>Add Failed</p>");
			 	window.setTimeout(function(percentElement){
			 		percentElement.remove();
			 	},750,percentElement);
			 }
		 	//Then maybe do some more stuff here (ajax call to add something to say its in transmission :S)
		});
	}).css('cursor', 'pointer');

	$('div.downloading').each(function(){
		//console.log("div.downloading");
		//console.log($(this));
		element = $(this);
		xt = $(this).parent().attr('data-xt').toString();
		inter = setInterval(function(xt,element){
			//console.log("getPercentage" + xt);
			getPercentage(xt,element);
		},200,xt,element);
		//console.log("Adding repeating function to " + xt + " - " + inter);
	});

	$('span.imdbModal').click(function(){
		console.log('clicked');
		imdb = $(this).attr("data-imdbCode").toString();
		src = "http://www.imdb.com/title/" + imdb;

		$.getJSON("http://noembed.com/embed?url=" + src, function( data ){
			//console.log("DATA" + data.html);
			$.modal(data.html, {
				closeHTML:"",
				containerCss:{ 
					"border-radius":"5px",
					backgroundColor:"#000",
					borderColor:"#000", 
					"max-height":'50%', 
					"max-width":'50%',
				},
				overlayClose:true
			});
		});
		//src = "https://www.google.co.uk/?gfe_rd=cr&ei=ON6iU6fCO-_R8ger5ICwAw&gws_rd=ssl#q=test&output=embed"
		//var src = "http://www.google.co.uk";
		//$.modal("<iframe src='http://www.imdb.com/title/" + imdb + "'></iframe>");
		
		//$.modal("<div><h1>SimpleModal</h1></div>");
	});

});

