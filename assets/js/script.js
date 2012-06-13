/* Author:

*/

$(function() {
    //log('script init');

   $('table.piechart-data').each(function() {
       var cpt_line= $(this).find('tbody tr').length;
       if (cpt_line>1) init_piechart( $(this) );
    });

     $('div.ttgraph').each(function() {
        graph=init_graph( $(this) );
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
    self.timelapse=gettimelapse( self.json_param.group_by );

    self.updateJsonParam= function( id, data ) {
        self.json_param[id]=data;
        if (self.graph) self.loadJson( function(){ self.graph.update(); });
        }

    self.loadJson= function( callback ) {
        var url= BASE_URL+'tt/'+self.json_param.username+'/histo/'+self.json_param.type_cat+'/'+self.json_param.id+'/'+self.json_param.date_plage+'/'+self.json_param.group_by+'.json';
        $.getJSON(url, function(data) {  self.data=data; callback()   });
    }

    self.buildGraph=function () {
        if (self.json_param.type_graph== 'histo') self.graph=self.histograph();
    }

    //init
    self.loadJson( function(){ self.buildGraph() } );


    function gettimelapse(timelapsename) {
        var r={
            second:     1,
            minute:     60,
            hour:       60*60,
            day:        60*60*24,
            week:       60*60*24*7
            }
        return r[timelapsename]
    }







    /* histograph */
    self.histograph=function() {

        var histograph_obj={};

        var w = 1170, h = 600, color = d3.scale.category20();

        var vis = d3.select(self.target[0]).append("svg:svg")
            .attr("width", w)
            .attr("height", h+20)
            .attr("viewBox",0+" "+0+" "+w+" "+(h+20));

        var graphgroup= vis.append("svg:g").attr("id", "graph_g");
        vis.append("svg:g").attr("id", "xaxis_g");
        vis.append("svg:g").attr("id", "yaxis_g");


        histograph_obj.update=function() {
            var data=self.data;
            var bar_width= (1/2) * w/self.data.times.length;

            var fx = d3.scale.linear().domain([data.min, data.max+self.timelapse]).range([50, w]);

            var fy = d3.scale.linear().domain([0, d3.max(data.times, function(d){ return d.total } )]).range([h, 10]);
            var fh = d3.scale.linear().domain([0, d3.max(data.times, function(d){ return d.total } )]).range([0, h-10]);

            var f_yaxis = d3.scale.linear().domain([0, d3.max(data.times, function(d){ return d.total/(60*60) } )]).range([h, 10]);
            var f_xaxis = d3.scale.linear().domain([data.min/self.timelapse, (data.max+self.timelapse)/self.timelapse]).range([50, w]);



            var format_date= function(axisdata) {
                var t= new Date(axisdata*self.timelapse*1000);
                var res=t.getDate()+'/'+t.getMonth()+'/'+t.getFullYear();
                if (self.timelapse<60*60*24) {
                    var h=t.getHours()+':'+t.getMinutes();
                    //if (h!='0:0') res=h;
                    res=res+' '+h;
                    }
                return res
            }


            var format_duration= function(duration) {

                duration*=60;

                if (duration==0) return '';

                var h= Math.floor(duration/60);
                var m= duration%60;

                if (m<10) m= '0'+m;


                res= h+':'+m;

                return res
            }



            var timegroups = vis.select('#graph_g').selectAll('g.timegroup').data(data.times, function(d) { return d.time;});

            timegroups.enter().append('svg:g')
                .attr('class', 'timegroup')
                .attr('transform', function(d) {    return 'translate('+fx(d.time)+',0)'       });

            timegroups.transition(2000).attr('transform', function(d) {    return 'translate('+fx(d.time)+',0)'       });

            timegroups.exit().remove();



                var activityrects = timegroups.selectAll('rect.activity').data(function(d) {return d.activities}, function(d) { return d.activity_ID });
                activityrects.enter().append('svg:rect')
                    .attr('class', 'activity')
                    .attr('width', function() {         return bar_width    })
                    .attr('y', fy(0) )
                    .attr('height', 0)
                    .attr('fill', function(d, i) {      return color(d.activity_ID)  })
                    .on('mouseover', function(d) {    /*log(d);*/ document.title=d.activity+' '+d.duration   });

                activityrects.transition(2000)
                    .attr('width', function() {         return bar_width    })
                    .attr('y', function(d,i) {
                        if (i==0) cumul=0;
                        var res=fy( cumul + d.duration);
                        cumul+=d.duration;
                        return res
                        })
                    .attr('height', function(d) {   return fh(d.duration)   });

                activityrects.exit().remove();



                var xaxis = vis.select('#xaxis_g').attr("transform", "translate(0," + h + ")");
                xaxis.transition(800).call(d3.svg.axis().scale(f_xaxis).ticks(10).tickFormat( function(d) { return format_date(d) } ) );


                var yaxis = vis.select('#yaxis_g').attr("transform", "translate(50,0)");
                 yaxis.transition(800).call( d3.svg.axis().orient('left').scale(f_yaxis).ticks(6).tickFormat( function(d) { return format_duration(d) } ) );

            }



            histograph_obj.update();
            return histograph_obj;
        }



    return self
}

test=function() {
    log(graph);
    if (graph.json_param.group_by!='hour') graph.updateJsonParam('group_by','hour');
        else  graph.updateJsonParam('group_by','day');
    }