Monitoring = {

    map: null,
    markers: null,
    markerImagesSource: '/bundles/smartcitypanel/images/map/',

	init: function(){
        this.initMap();
        this.search();
    },
    
    initMap: function(){

        // init map and tiles
        Monitoring.map = L.map('mapid', {
            center: [32.4356, 53.9209],
            zoom: 5,
            minZoom: 4,
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
        Monitoring.markers = L.markerClusterGroup({
        zoomToBoundsOnClick: true,
        removeOutsideVisibleBounds: true,
        animate: true,
        animateAddingMarkers: true,
        chunkedLoading: false,
        spiderfyOnMaxZoom: true,//spiderify effect when many points have same lat/lon
        showCoverageOnHover: true,
        iconCreateFunction: function(cluster) {
            //Grouping the cluster returned by the server, if 
            var markers = cluster.getAllChildMarkers();
            var markerCount = 0;
            markers.forEach(function(m){markerCount = markerCount + m.count;});
            return new L.DivIcon({ html: '<div class=cluster-icon leaflet-zoom-animated leaflet-clickable" tabindex="0"><div><span>'+markerCount+'</span></div></div>' });
            }
        });
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
        var zoom = 2;

        if(mapZoom >= 5 && mapZoom <= 8){
            zoom = 3;
        }
        else if(mapZoom >= 9 && mapZoom <= 11){
            zoom = 8;
        }
        else if(mapZoom >= 12 && mapZoom <= 13){
            zoom = 9;
        }
        else if(mapZoom >= 14){
            zoom = 10;
        }

        return {
            'bound': bound_object,
            'zoomLevel': zoom
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
    makePoints: function(clusters){
        points = {};
        var markerList = [];
        clusters.forEach(function(cluster, index){
            
            var center = geohash.decode(cluster.key);//elastic return a geohas so need to change it into lat/lon
            var specs = cluster.device.buckets[0].key.split('|');
            var marker_html = '<div data-id="'+specs[0]+'" class=cluster-icon leaflet-zoom-animated leaflet-clickable" tabindex="0"><div><span>'+cluster.doc_count+'</span></div></div>';
            
            if (cluster.doc_count == 1){                
                marker_html = '<div data-id="'+specs[0]+'" class="marker-image"><img width="32px" src="'+Monitoring.markerImagesSource + specs[1]+'.png"></div>';
            }
            
            
            var marker_icon = L.divIcon({ html: marker_html });
            var marker = L.marker(new L.LatLng(center.latitude, center.longitude), { 
                icon: marker_icon 
            });

            marker.on('click', function(){ Monitoring.getSensorDetail(specs[0]) });

            marker.count = cluster.doc_count;
            markerList.push(marker);
        });
        Monitoring.markers.addLayers(markerList);
    },

    getSensorDetail: function(sensor_id){
        console.log(sensor_id);
    }
}