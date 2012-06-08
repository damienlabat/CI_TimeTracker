/* Author:

*/

$(function() {
    //log('script init');

    $('div.camembert').each(function() { piecharts( $(this) ); });

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

function piecharts(obj) {
    var data_id= obj.attr('data-camembert-id').split(' ');

    if (data_id.length==1) { data=stats[ data_id[0] ];                  table_id='table_'+data_id[0]; }
    if (data_id.length==2) { data=stats[ data_id[0] ][ data_id[1] ];    table_id='table_'+data_id[0]+'_'+data_id[1]; }

    var graph=  new_piechart( obj[0], data, table_id );

    $('#'+table_id+' tr').each(function (i) {
        var id_slice=$(this).attr('data-slice-id');

        if (id_slice)
             $(this).hover(
                function () { graph.mouseover('slice'+id_slice) },
                function () { graph.mouseout('slice'+id_slice) }
            );
    });
}




 var new_piechart= function( div_target, data, table_id ) {
        var self={};

        self.div_target=div_target;
        self.table_id=table_id;
        self.data=data;
        self.data_chart=[];

        for (i in self.data) {
            var n_obj={ "label":data[i].title, "value":data[i].total, "id":i };
            if (typeof(data[i].activity_path)!=='undefined') n_obj.label=data[i].activity_path;
            if (typeof(data[i].tag)!=='undefined') n_obj.label=data[i].tag;
            self.data_chart.push( n_obj );
        }

        var w=500, h=300, r=150, color = d3.scale.category20c();

        var vis = d3.select(self.div_target)
            .append("svg:svg")
            .data([self.data_chart])
                .attr("width", w)
                .attr("height", h)
                .attr("viewBox",0+" "+0+" "+w+" "+h)
            .append("svg:g")
                .attr("transform", "translate(" + w/2 + "," + h/2 + ")");

        var arc = d3.svg.arc()
            .innerRadius(1/2*r)
            .outerRadius(3/4*r);

         var arc_hover = d3.svg.arc()
            .innerRadius(1/2*r)
            .outerRadius(r);

        var pie = d3.layout.pie()
            .value(function(d) { return d.value; });

        var arcs = vis.selectAll("g.slice")
            .data(pie)
            .enter()
                .append("svg:g")
                    .attr("id", function(d) { return 'slice'+d.data.id;  })
                    .on("mouseover", function(){
                        self.mouseover( this.id );
                        })
                    .on("mouseout",  function(){
                        self.mouseout( this.id  );
                        })
                    .attr("class", "slice");

            arcs.append("svg:path")
                    .attr("fill", function(d, i) { return color(i); } )
                    .attr("d", arc);

            arcs.append("svg:text")
                .attr("text-anchor", "middle")
                .text(function(d, i) { return d.data.label; });


        self.mouseover= function(id) {
            var gslice=d3.select(self.div_target).select('#'+id);
            gslice.selectAll("path").transition().attr("d", arc_hover );
            gslice.selectAll("text").transition().style("opacity", "1");
            $('#'+self.table_id+' .tr'+id).addClass('hover');
        }

        self.mouseout= function(id) {
            var gslice=d3.select(self.div_target).select('#'+id);
            gslice.selectAll("path").transition().attr("d", arc );
            gslice.selectAll("text").transition().style("opacity", "0");
            $('#'+self.table_id+' .tr'+id).removeClass('hover');
        }

        return self

    }