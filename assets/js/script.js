/* Author:

*/

$(function() {
    log('script init');

    $('div.camembert').each(function() { init_camembert( $(this) ); });

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

function init_camembert(obj) {
    var data_id= obj.attr('data-camembert-id').split(' ');
    var data=[];
    if (data_id.length==1) new_piechart( obj[0], stats[ data_id[0] ] );
    if (data_id.length==2) new_piechart( obj[0], stats[ data_id[0] ][ data_id[1] ] );

    }


 var new_piechart= function( div_target, data ) {

        var data_chart=[];

        for (i in data) {
            var n_obj={ "label":data[i].title, "value":data[i].total };
            if (typeof(data[i].activity_path)!=='undefined') n_obj.label=data[i].activity_path;
            if (typeof(data[i].tag)!=='undefined') n_obj.label=data[i].tag;
            data_chart.push( n_obj );
        }

        var w=300, h=w, r=w/2, color = d3.scale.category20c();

        var vis = d3.select(div_target)
            .append("svg:svg")
            .data([data_chart])
                .attr("width", w)
                .attr("height", h)
            .append("svg:g")
                .attr("transform", "translate(" + w/2 + "," + h/2 + ")")

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
                    .on("mouseover", function(){
                        d3.select(this).selectAll("path").transition().attr("d", arc_hover );
                        d3.select(this).selectAll("text").transition().style("opacity", "1");
                        })
                    .on("mouseout",  function(){
                        d3.select(this).selectAll("path").transition().attr("d", arc );
                        d3.select(this).selectAll("text").transition().style("opacity", "0");
                        })
                    .attr("class", "slice");

            arcs.append("svg:path")
                    .attr("fill", function(d, i) { return color(i); } )
                    .attr("d", arc);

            arcs.append("svg:text")
                .attr("text-anchor", "middle")
                .text(function(d, i) { return data_chart[i].label; });


        return this
        }