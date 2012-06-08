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

    function group_by_day(data) {
        log(data);
        var res={min:null, max:null, days:{}};
        for (id in data) {
            var start_time= new Date(data[id].UNIX_start_time*1000);
            var stop_time= new Date((data[id].UNIX_start_time+data[id].duration)*1000);
            var day=get_midnight_before(start_time);

            if (day.getTime()/1000<res.min || !res.min) res.min=day.getTime()/1000;

            while (day.getTime() <= get_midnight_before(stop_time).getTime()) {

                if (day.getTime()/1000>res.max) res.max=day.getTime()/1000;

                if (!res.days[day.getTime()/1000]) res.days[day.getTime()/1000]=[];

                var next_day= new Date( day.getTime() + 24*60*60*1000 );

                res.days[day.getTime()/1000].push( trim_duration(data[id] ,day, next_day) ); // todo trim duration

                day= next_day;
            }


        }
log(data);
    return res
    }

    function get_midnight_before(date) {
        return new Date( date.getFullYear()+' '+(date.getMonth()+1)+' '+date.getDate());
        }

    function trim_duration(record,datemin,datemax) {
        var res=jQuery.extend(true, {}, record);
        var t1=datemin.getTime()/1000;
        var t2=datemax.getTime()/1000;
        var start_time= res.UNIX_start_time;
        var stop_time= start_time+res.duration;

        if (start_time<t1)  { start_time=t1; res.UNIX_start_time=t1; res.start_time='todo'; }
        if (stop_time>t2)   stop_time=t2;

        res.duration= stop_time-start_time;
        res.stop_at='todo';

        return res;
    }


    /* histograph */
    self.histograph=function() {

        var groupdata= group_by_day(self.data);
        log(groupdata);

    }

    return self
}