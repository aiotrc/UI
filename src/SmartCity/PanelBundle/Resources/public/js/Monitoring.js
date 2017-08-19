Monitoring = {

    map: null,
    markers: null,

	init: function(){
        this.initMap();
        this.search();
    },
    
    initMap: function(){

        // init map and tiles
        Monitoring.map = L.map('mapid', {
            center: [32.4356, 53.9209],
            zoom: 5,
            // minZoom: 3,
            maxzoom: 12
        });

        // http://{s}.tile.osm.org/{z}/{x}/{y}.png
        var tiles = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoicG9vdHJpYSIsImEiOiJjajZiOGJ1amsxZXgzMndvOThvZTJ1bnY3In0.hcwyouLNbyr6Qsl4-nmpsg', {
            id: 'mapbox.streets',
        }).addTo(Monitoring.map);
        

        // init map listeners
        Monitoring.map.on('load', function(e){Monitoring.search()});
        Monitoring.map.on('zoomend dragend',function(e){Monitoring.search();});


        // init marker clustering
        // Monitoring.markers = L.markerClusterGroup({
        // chunkedLoading: true,
        // spiderfyOnMaxZoom: true,//spiderify effect when many points have same lat/lon
        // showCoverageOnHover: true,
        // iconCreateFunction: function(cluster) {
        //     //Grouping the cluster returned by the server, if 
        //     var markers = cluster.getAllChildMarkers();
        //     var markerCount = 0;
        //     markers.forEach(function(m){markerCount = markerCount + m.count;});
        //     return new L.DivIcon({ html: '<div class=" clustergroup0 leaflet-marker-icon marker-cluster marker-cluster-medium leaflet-zoom-animated leaflet-clickable" tabindex="0" style="margin-left: -20px; margin-top: -20px; width: 40px; height: 40px; z-index: 233;"><div><span>'+markerCount+'</span></div></div>' });
        //     }
        // });
        // Monitoring.map.addLayer(Monitoring.markers);

        Monitoring.markers = L.layerGroup();
        Monitoring.map.addLayer(Monitoring.markers);
    },

    getMapParams: function(){

        // Get map bound
        var bound = Monitoring.map.getBounds();

        var bound_object = {
            "top_left_lat": bound.getNorthWest().wrap().lat,
            "top_left_lon": bound.getNorthWest().wrap().lng,
            "bottom_right_lat": bound.getSouthEast().wrap().lat,
            "bottom_right_lon": bound.getSouthEast().wrap().lng,
        }

        var mapZoom = Monitoring.map.getZoom();
        var zoomLevel = 3;

        if(mapZoom >= 9 && mapZoom <= 11){
            zoomLevel = 4;
        }
        else if(mapZoom >= 12 && mapZoom <= 14){
            zoomLevel = 5;
        }
        else if(mapZoom >= 15 && mapZoom <= 17){
            zoomLevel = 6;
        }
        else if(mapZoom >= 15){
            zoomLevel = 7;
        }

        return {
            'bound': bound_object,
            'zoomLevel': zoomLevel
        }
    },

    search: function() {
        map_params = this.getMapParams();

        $.ajax({
            url: Routing.generate('panel_monitoring_map_marker_cluster'),
            method: "POST",
            data: map_params,
        })
	    .done(function(response) {
            Monitoring.markers.clearLayers();
	        Monitoring.makePoints(response.markers.buckets);
    	})
    	.fail(function(error) {
        	BackendFramework.showNotif('error')
    	});
    },

    /* This will add all the clusters as returned by the elastic server.*/ 
    makePoints: function (clusters){
        points = {};
        var markerList = [];
        clusters.forEach(function(cluster, index){

            var id = cluster.doc_count;
            if (cluster.doc_count == 1) {
                // id = cluster.centroid.buckets[0].key
            }

            // var myIcon = L.divIcon({ html: '<div class="clustergroup0 leaflet-marker-icon marker-cluster marker-cluster-medium leaflet-zoom-animated leaflet-clickable" tabindex="0" style="margin-left: -20px; margin-top: -20px; width: 40px; height: 40px; z-index: 233;"><div><span>'+cluster.doc_count+'</span></div></div>' });
            // var center = geohash.decode(cluster.key); //elastic return a geohas so need to change it into lat/lon
            var center = cluster.centroid.location //elastic return a geohas so need to change it into lat/lon
            var marker = L.marker(new L.LatLng(center.lat, center.lon));
            // marker.count = cluster.doc_count;
            // marker.bindPopup(''+id);
            markerList.push(marker);
        });
        Monitoring.map.addLayer(markerList);
    }
}