/**
 * Show marker and zoom to the its coordinates.
 *
 * @param o    		Openlayer itself
 * @param m    		Map
 * @param e    		Mouse event
 * @param mr  		Marker
 * @param z    		Target zoom level
 * @param auto_zoom Auto-zoom (default is false)
 */
function e_get_factory_location(o, m, e, mr, z, auto_zoom) {
	var proj = e.coordinate;
	var lonlat = o.proj.transform(proj, 'EPSG:3857', 'EPSG:4326');
	auto_zoom = auto_zoom || false;
	
	e_set_factory_location(o, m, lonlat[1], lonlat[0], mr, z, auto_zoom);
	
	return lonlat;
}

/**
 * Show marker and zoom to the its coordinates.
 *
 * @param o    		Openlayer itself
 * @param m    		Map
 * @param lat		Latitude
 * @param lon		Longitude
 * @param mr  		Marker
 * @param z    		Target zoom level
 * @param auto_zoom Auto-zoom (default is false)
 */
function e_set_factory_location(o, m, lat, lon, mr, z, auto_zoom) {
	console.log(lat, lon, z);
	var proj = o.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857');
	mr.setCoordinates(proj);
	console.log(proj);
	auto_zoom = auto_zoom || false;
	
	if(auto_zoom == true) {
		zoom_to_factory(o, m, lat, lon, z);
	}
}