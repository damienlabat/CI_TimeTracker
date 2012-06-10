/* Author:

*/

$(function() {
    //log('script init');

   $('table.piechart-data').each(function() {
       var cpt_line= $(this).find('tbody tr').length;
       if (cpt_line>1) init_piechart( $(this) );
    });

     $('div.ttgraph').each(function() {
        init_graph( $(this) );
    });

});





// lightweight wrapper for console.log
// usage: log('inside coolFunc',this,arguments);
// http://paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console){
    console.log( Array.prototype.slice.call(arguments) );
  }
};









/*** d3.js ***/

/** piechart **/

function init_piechart(obj) {
    var target= obj.parent().parent().find('.piechart-target');
    piechart= new_piechart(obj,target,300);

}



 var new_piechart= function( data_table, target, width ) {
        var self={};

        if (!width) width=200;

        self.data_table=data_table;
        self.target=target;

        self.data=[];
        self.tr=[];

        data_table.find('tr').each(function() {
            var obj={};
            obj.id= $(this).attr('data-id');
            obj.value= $(this).attr('data-value');
            if (obj.value && obj.id) {
                self.data.push(obj);
                self.tr['slice_'+obj.id]= $(this);

                $(this).hover(  // TODO add focus on off link
                    function () { self.mouseover('slice_'+obj.id) },
                    function () { self.mouseout('slice_'+obj.id) }
                );
            }
        });


        var w=width, h=w, r=w/2, color = d3.scale.category20c();

        var vis = d3.select(self.target[0])
            .append("svg:svg")
            .data([self.data])
                .attr("width", w)
                .attr("height", h)
                .attr("viewBox",0+" "+0+" "+w+" "+h)
            .append("svg:g")
                .attr("transform", "translate(" + w/2 + "," + h/2 + ")");

        var arc = d3.svg.arc()
            .outerRadius(3/4*r);

         var arc_hover = d3.svg.arc()
            .outerRadius(r);

        var pie = d3.layout.pie()
            .value(function(d) { return d.value; });

        var arcs = vis.selectAll("g.slice")
            .data(pie)
            .enter()
                .append("svg:g")
                    .attr("id", function(d) { return 'slice_'+d.data.id;  })
                    .on("mouseover", function(){
                        self.mouseover( this.id );
                        })
                    .on("mouseout",  function(){
                        self.mouseout( this.id );
                        })
                    .attr("class", "slice");

            arcs.append("svg:path")
                    .attr("fill", function(d, i) { return color(i); } )
                    .attr("d", arc);




        self.mouseover= function(id) {
            var gslice=d3.select(self.target[0]).select('#'+id);
            gslice.selectAll("path").transition().attr("d", arc_hover );
            self.tr[id].addClass('hover');
        }

        self.mouseout= function(id) {
            var gslice=d3.select(self.target[0]).select('#'+id);
            gslice.selectAll("path").transition().attr("d", arc );
            self.tr[id].removeClass('hover');
        }

        return self

    }

/** graph **/

function init_graph(obj) {
    var self={};
    self.target=obj;
    self.json_param=jQuery.parseJSON(self.target.attr( 'data-graph' ));
    var url= BASE_URL+'tt/'+self.json_param.username+'/export/'+self.json_param.type_cat+'/'+self.json_param.id+'/'+self.json_param.date_plage+'/json';
    $.getJSON(url, function(data) {  self.data=data; self.buildgraph()   });

    self.buildgraph=function () {
        if (self.json_param.type_graph== null) self.histograph();
    }

    /* group order data */

    function group_by_time(data,timelapse) { // recouper en fonction de la page choisie par utilisateur (GET plage_date)

        var res={'min':null, 'max':null};
        var times={};


        for (id in data) {
            var start_time= new Date(data[id].UNIX_start_time*1000);
            var stop_time= new Date((data[id].UNIX_start_time+data[id].duration)*1000);
            var time=get_time_before(start_time,timelapse);

           if (time.getTime()/1000<res.min || !res.min) res.min=time.getTime()/1000;

            while (time.getTime() <= get_time_before(stop_time,timelapse).getTime()) {

                var timeUNIX=time.getTime()/1000;

                if (timeUNIX>res.max) res.max=timeUNIX;

                if (!times[timeUNIX]) times[timeUNIX]=[];

                if (!times[timeUNIX][data[id].activity.id]) var obj= { activity: data[id].activity, duration:0, time:timeUNIX  }
                    else var obj=times[timeUNIX][data[id].activity.id];

                var next_time= new Date( time.getTime() + timelapse*1000 );
                obj.duration += trim_duration(data[id] ,time, next_time);

                times[timeUNIX][data[id].activity.id]=obj;

                time= next_time;
            }

        }

        res_times=[];
        for (d=res.min; d<=res.max; d+=timelapse) {
            activities=[];
            if (times[d])
                for (id in times[d])
                    activities.push( times[d][id] );

            res_times.push({ 'time':d, 'activities':activities })
         }

        res.times=res_times;

    return res
    }


    function get_time_before(date,timelapse) {
        return new Date( Math.floor(date.getTime()/(timelapse*1000)) * (timelapse*1000) );
        }

    function trim_duration(record,datemin,datemax) {
        var t1=datemin.getTime()/1000;
        var t2=datemax.getTime()/1000;
        var start_time= record.UNIX_start_time;
        var stop_time= start_time+record.duration;

        if (start_time<t1)  start_time=t1;
        if (stop_time>t2)   stop_time=t2;

        duration= stop_time-start_time;

        return duration;
    }





    /* histograph */
    self.histograph=function() {

        var timelapse=60*60*1;

        var groupdata= group_by_time(self.data,timelapse);

        var min_date=groupdata.min;
        var max_date=groupdata.max;

        var nb_bar=(max_date-min_date)/timelapse+1;

        var w=600, h=400, color = d3.scale.category20c();

        var l_bar=w/nb_bar* (4/5);
        var e_bar=w/nb_bar* (1/5);


        var vis = d3.select(self.target[0])
            .append("svg:svg")
                .attr("width", w)
                .attr("height", h)
                .attr("viewBox",0+" "+0+" "+w+" "+h);

        var posbar = function(d,i) { return "translate(" + (i*(l_bar+e_bar)) + ",0)"; };

        var f_y_bar= function(d,i) {
            if (i==0) ybar=400;
            ybar -= h_bar(d,i);
            return ybar;
        }

        var h_bar= function(d,i) {
            return d.duration/50;
        }



        var day_g = vis.selectAll("g")
            .data(groupdata.times)
            .enter()
                .append("svg:g")
                .attr("transform", posbar)
                .selectAll("rect")
                .data( function(d) { return d.activities } )
                .enter()
                    .append("svg:rect")
                    .attr('y',      f_y_bar)
                    .attr('width',  l_bar)
                    .attr('height', h_bar )
                    .attr("fill",   function(d) { return color(d.activity.id); } )
                    .on("mouseover", function(d){ document.title=d.time+' '+d.activity.activity_path + ' ' + Math.round(d.duration/60) +' min'   });


    }

    return self
}