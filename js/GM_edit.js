function gp_init_inline_edit(area_id, section_object) {

	loaded()
	gp_editing.editor_tools()
	var edit_div = gp_editing.get_edit_area(area_id)
	var cache_value = ''

	gp_editor = {
		save_path: gp_editing.get_path(area_id),
		destroy: function () {
		},
		checkDirty: function () {
			var curr_val = gp_editor.gp_saveData()
			if (curr_val != cache_value) {
				return true
			}
			return false
		},
		resetDirty: function () {
			cache_value = gp_editor.gp_saveData()
		},

		gp_saveData: function () {

			var zoom = map.getZoom()
			var tosave = []
			$.each(markers, function (key, value) {
				var lat = value.getPosition().lat()
				var lng = value.getPosition().lng()
				var coord = lat + '_' + lng
				var test = { coord: coord, info: value.html }
				tosave.push(test)

			})

			var json = ''
			$.each(tosave, function () {
				json += '&markers[' + this.coord + '][info]=' + this.info + '&markers[' + this.coord + '][coords]=' + this.coord//not really json hehe
			})

			var options_my = $('#gp_my_options').find('input,select').serialize()
			return '&' + options_my + '&zoom=' + zoom + '&markers=' + json

		},
		intervalSpeed: function () {
		},
		selectUsingFinder: function () {
		},
		setImage: function () {
		},
		check_image: function () {
		},
		updatesect: function () {
		},
		updateElement: function () {
		},
	}// gpeditor --end


	var option_area = $('<div id="gp_my_options"/>').prependTo('#ckeditor_controls')

	var option_messages = $(
		'<div id="option_message">' +

		'<div class="a_box">' +
		'<div class="switch-field">                                                      ' +
		'  <div class="switch-title">Default map type</div>                          ' +
		'  <input type="radio" id="Roadmap" name="map_type" value="0" />    ' +
		'  <label  for="Roadmap">Roadmap</label>                                          ' +
		'  <input type="radio" id="Satellite" name="map_type" value="1" />       ' +
		'  <label  for="Satellite">Satellite </label>                                        ' +
		'  <input type="radio" id="Hybrid" name="map_type" value="2" />       ' +
		'  <label for="Hybrid">Hybrid</label>                                        ' +
		'  <input type="radio" id="Terrain" name="map_type" value="3" />     ' +
		'  <label for="Terrain">Terrain  </label>                                       ' +
		'</div>                                                                            ' +
		'</div>' +

		'<div class="a_box">' +
		'<div class="a_line">' +
		'<label class="switch"><input type="checkbox" name="Bouncemarker" value="Bouncemarker" id="Bouncemarker"/><div class="slider round"></div></label>' +
		'<p>Bounce markers? </p>' +
		'</div>' +
		'</div>' +
		'<div class="a_box">' +
		'<div class="a_line">' +
		'<label class="switch"><input type="checkbox" name="fullscreen" value="fullscreen" id="fullscreen"/><div class="slider round"></div></label><p>Show fullscreen button?</p>' +
		'</div>' +
		'</div>' +

		'<div class="a_box">' +

		'<div class="a_line">' +
		'<label class="switch"> <input type="checkbox" name="responsive" value="responsive" id="responsive"/><div class="slider round"></div></label><p>Use responsive style?</p>' +
		'</div>' +

		'<div class="resp_opt">' +
		'<p>Width of map(px) ' +
		'<input  type="number" step="1" name="sizeW"  id="sizeW" value="' + section_object.sizeW + '" class="a_inp"  />' +
		'</p>' +
		'<p>Height of map(px) ' +
		'<input type="number" step="1" name="sizeH" id="sizeH" value="' + section_object.sizeH + '" class="a_inp" />' +
		'</p>' +
		'</div>' +
		'<div class="resp_opt">' +
		'<p>Ratio (%)<br/>' +
		'<input  type="number" min="0" step="0.01" id="ratio" name="ratio"  value="' + section_object.ratio + '" class="a_inp"  />' +
		' for 16:9 (100%/16*9 = 56.25%)' +
		' for 4:3 (100%/4*3 = 75.00%)' +
		'</p>' +
		'</div>' +

		'</div>' + //box end
		'<input id="mc" type="hidden" value="true" name="mc" value="' + section_object.mc + '"/>' +
		'<p> Custom icon for marker</p>' +
		'<button id="getImageButton" class="a_flat_button">Select Image</button> ' +
		'<button id="reset" class="a_flat_button">Reset</button> ' +
		'<input id="CustomIcon" type="text" value="' + section_object.CustomIcon + '" name="CustomIcon" class="a_inp"  />' +
		'<p>Disable drag map for screen size smaller than (px)<br/>' +
		'<input  type="number" step="1" name="dragheight" value="' + section_object.dragheight + '" class="a_inp"  />' +
		'</p>' +

		'</div>' +
		'<div class="a_box">' +
		'<p><i>Made by Sitelab</i></p>' +
		'<a id="stl" href="http://my-sitelab.com/" target="_blank"><img alt="Sitelab" src="' + section_object.addonRelativeCode + '/img/st_logo.jpg"  /></a>' +
		'<p class="stl">We can help with the development of the beautiful site of any complexity</p>' +
		'</div>',
	).appendTo(option_area)


	if (section_object.Bouncemarker) {
		$('#gp_my_options').find('#Bouncemarker').prop('checked', true)
	}
	if (section_object.fullscreen) {
		$('#gp_my_options').find('#fullscreen').prop('checked', true)
	}
	if (section_object.responsive) {
		$('#gp_my_options').find('#responsive').prop('checked', true)
		$('#gp_my_options').find('.resp_opt').first().addClass('hide')
	} else {
		$('#gp_my_options').find('.resp_opt').eq(1).addClass('hide')
	}


	//set map vars
	var map_container = edit_div.find('.mapCanvas')
	var z = parseInt(section_object.zoom)
	var styl = edit_div.find('.GMStyle').val()
	if (styl != '') {
		var styl = JSON.parse(styl)
	}
	var ico = $('#gp_my_options').find('#CustomIcon').val()
	var wrapper = $('#map_data')


	var map = new google.maps.Map(map_container[0], {
		center: { lat: 0, lng: 0 },
		zoom: z,
		styles: styl,
	})

	markers = {}

	var ani = $('#gp_my_options').find('#Bouncemarker').prop('checked') ? google.maps.Animation.BOUNCE : null

	$('#gp_my_options').find('#Bouncemarker').click(function () {
		if ($(this).prop('checked')) {
			var ani = google.maps.Animation.BOUNCE
			$.each(markers, function (index, marker) {
				marker.setAnimation(google.maps.Animation.BOUNCE)
			})
		} else {
			var ani = null
			$.each(markers, function (index, marker) {
				marker.setAnimation(null)
			})
		}
	})


	var getMarkerUniqueId = function (lat, lng) {
		return lat + '_' + lng
	}

	var getLatLng = function (lat, lng) {
		return new google.maps.LatLng(lat, lng)
	}

	var addMarker = google.maps.event.addListener(map, 'click', function (e) {
		var lat = e.latLng.lat()
		var lng = e.latLng.lng()
		var markerId = getMarkerUniqueId(lat, lng)
		var marker = new google.maps.Marker({
			position: getLatLng(lat, lng),
			map: map,
			animation: ani,
			icon: ico,
			draggable: true,
			id: 'marker_' + markerId,
			html: 'You can edit the text of this infowindow.<br> Html also possible',
		})

		setEditableInfo(marker)

		markers[markerId] = marker // cache marker in markers object
		bindMarkerEvents(marker) // bind rightclick event to marker
		AutoCenter()
	})

	var bindMarkerEvents = function (marker) {
		google.maps.event.addListener(marker, 'rightclick', function (point) {
			var markerId = getMarkerUniqueId(point.latLng.lat(), point.latLng.lng()) // get marker id by using clicked point's coordinate
			var marker = markers[markerId] // find marker
			removeMarker(marker, markerId) // remove it
		})
	}
	var removeMarker = function (marker, markerId) {
		marker.setMap(null) // set markers setMap to null to remove it from map
		delete markers[markerId] // delete marker instance from markers object
		AutoCenter()
	}

	var setEditableInfo = function (marker) {

		marker.set('editing', false)

		var htmlBox = document.createElement('div')
		htmlBox.innerHTML = marker.html || ''
		htmlBox.style.width = '300px'
		htmlBox.style.height = '100px'

		var textBox = document.createElement('textarea')
		textBox.innerText = marker.html || ''
		textBox.style.width = '300px'
		textBox.style.height = '100px'
		textBox.style.display = 'none'

		var container = document.createElement('div')
		container.style.position = 'relative'
		container.appendChild(htmlBox)
		container.appendChild(textBox)

		var editBtn = document.createElement('button')
		editBtn.innerText = 'Edit'
		container.appendChild(editBtn)

		var infoWnd = new google.maps.InfoWindow({
			content: container,
		})

		google.maps.event.addListener(marker, 'click', function () {
			infoWnd.open(marker.getMap(), marker)
		})

		google.maps.event.addDomListener(editBtn, 'click', function () {
			marker.set('editing', !marker.editing)
		})

		google.maps.event.addListener(marker, 'editing_changed', function () {
			textBox.style.display = this.editing ? 'block' : 'none'
			htmlBox.style.display = this.editing ? 'none' : 'block'
		})

		google.maps.event.addDomListener(textBox, 'change', function () {
			htmlBox.innerHTML = textBox.value
			marker.set('html', textBox.value)
		})

	}

	var setMarker = function (lat, lng, info) {
		var markerId = getMarkerUniqueId(lat, lng)
		var marker = new google.maps.Marker({
			position: getLatLng(lat, lng),
			map: map,
			animation: ani,
			icon: ico,
			draggable: true,
			id: 'marker_' + markerId,
			html: info,
		})
		setEditableInfo(marker)
		markers[markerId] = marker
		bindMarkerEvents(marker)
	}

	var setMarkers = function () {
		if (section_object.markers == '') return
		$.each(section_object.markers, function (key, value) {
			var res = value.coords.split('_')
			setMarker(res[0], res[1], value.info)
		})

	}

	setMarkers()

	/* if many markers auto center and zoom, if one  marker set desiraed zoom*/
	function AutoCenter() {
//  Create a new viewpoint bound
		var bounds = new google.maps.LatLngBounds()

		var count = 0
		$.each(markers, function (index, marker) {
			bounds.extend(marker.position)
			latLng1 = marker.getPosition()
			count++
		})
//  Fit these bounds to the map or-- center and set zoom in case of one marker
		if (count > 1) {
			map.fitBounds(bounds)
		} else {
			if (typeof latLng1 !== 'undefined') {
				map.setCenter(latLng1)
			}
			map.setZoom(z)
		}

	}

	AutoCenter()


	$('#gp_my_options').find('#reset').click(function () {
		$('#gp_my_options').find('#CustomIcon').val('')
		redraw_markers('')
		ico = ''

	})

	$('#gp_my_options').find('#getImageButton').on('click', function () {
		gp_editor.selectUsingFinder(gp_editor.setImage)
	})


	gp_editor.selectUsingFinder = function (callback_fn) {
		gp_editor.FinderSelect = function (fileUrl) {
			if (fileUrl != '') {
				callback_fn(fileUrl)
			}
			return true
		}
		var finderPopUp = window.open(gpFinderUrl, 'gpFinder', 'menubar=no,width=960,height=450')
		if (window.focus) {
			finderPopUp.focus()
		}
	}


	gp_editor.setImage = function (fileUrl) {
		if (gp_editor.check_image(fileUrl)) {
			$('#gp_my_options').find('#CustomIcon').val(fileUrl)
			ico = fileUrl.toString()
			redraw_markers(fileUrl)

		}
	}
	if (!$('#gp_my_options').find('#stl').length) {
		$('#mc').val('false')
	}
	if ($('#gp_my_options').find('#stl').attr('href') != 'http://my-sitelab.com/') {
		$('#mc').val('false')
	}

	var redraw_markers = function (fileUrl) {
		$.each(markers, function (index, marker) {
			marker.setIcon(fileUrl.toString())
			marker.setMap(null)
		})

		$.each(markers, function (index, marker) {
			marker.setMap(map)
		})

	}

	gp_editor.check_image = function (fileUrl) {
		var filetype = fileUrl.toString().substr(fileUrl.lastIndexOf('.') + 1).toLowerCase()
		if (!filetype.match(/jpg|jpeg|png|gif|svg|svgz|mng|apng|webp|bmp|ico/)) {
			window.setTimeout(
				function () {
					alert('Please choose an image file! '
						+ '\nValid file formats are: *.jpg/jpeg, *.png/mng/apng, '
						+ '*.gif, *.svg/svgz, *.webp, *.bmp, *.ico')
				}, 300,
			)
			return false
		}
		return true
	}

	//ratio change
	$('#ratio').on('input', function (e) {
		var temp = parseFloat(this.value).toFixed(2)
		if (!temp) return
		if (temp > 200) return
		edit_div.find('.r_map').css('padding-bottom', temp + '%')
		MapResize()
	})

	$('#sizeH').on('input', function (e) {
		if (!$('#gp_my_options').find('#responsive').prop('checked')) {
			map_container.css({ 'height': parseInt(this.value) + 'px' })
			MapResize()
		}
	})
	$('#sizeW').on('input', function (e) {
		if (!$('#gp_my_options').find('#responsive').prop('checked')) {
			map_container.css({ 'width': parseInt(this.value) + 'px' })
			MapResize()
		}
	})


	//responsive change
	$('#gp_my_options').find('#responsive').click(function () {
		$('#gp_my_options').find('.resp_opt').toggleClass('hide')
		var current_height = parseInt($('#gp_my_options').find('#sizeH').val())
		var current_width = parseInt($('#gp_my_options').find('#sizeW').val())
		var current_ratio = parseFloat($('#gp_my_options').find('#ratio').val()).toFixed(2)
		if ($(this).prop('checked')) {
			map_container.css({ 'width': '', 'height': '', 'padding-bottom': current_ratio + '%' })
			map_container.addClass('r_map')
			edit_div.find('.map-container').css({ 'width': '' })
			MapResize()
		} else {
			map_container.removeClass('r_map')
			map_container.css({
				'width': current_width + 'px',
				'height': current_height + 'px',
				'padding-bottom': '',
			})
			edit_div.find('.map-container').css({ 'width': current_width + 'px' })
			MapResize()
		}

	})

	//fullscreen change
	$('#gp_my_options').find('#fullscreen').click(function () {
		if ($(this).prop('checked')) {
			if ($('#gp_my_options').find('#responsive').prop('checked')) {
				var style_c = 'width:100%;margin-left: auto;margin-right: auto;	position:relative;'
			} else {
				var current_width = parseInt($('#gp_my_options').find('#sizeW').val())
				var style_c = 'width:' + current_width + 'px;margin-left: auto;margin-right: auto;	position:relative;'
			}

			map_container.wrap('<div class="map-container" style="' + style_c + '"></div>')

			$(' <div class="btn-full-screen">' +
				' <a id="btn-enter-full-screen"><img src="' + section_object.addonRelativeCode + '/img/fullscreen_enter.png"/></a>' +
				'<a id="btn-exit-full-screen"><img src="' + section_object.addonRelativeCode + '/img/fullscreen_exit.png "/></a>' +
				'</div>').insertBefore(map_container)


		} else {
			edit_div.find('.btn-full-screen').remove()
			map_container.unwrap()
		}


	})

	//maptype
	switch (section_object.map_type) {
		case '0':
			$('#gp_my_options').find('#Roadmap').prop('checked', true)
			map.setMapTypeId(google.maps.MapTypeId.ROADMAP)
			break
		case '1':
			$('#gp_my_options').find('#Satellite').prop('checked', true)
			map.setMapTypeId(google.maps.MapTypeId.SATELLITE)
			break
		case '2':
			$('#gp_my_options').find('#Hybrid').prop('checked', true)
			map.setMapTypeId(google.maps.MapTypeId.HYBRID)
			break
		case '3':
			$('#gp_my_options').find('#Terrain').prop('checked', true)
			map.setMapTypeId(google.maps.MapTypeId.TERRAIN)
			break
	}

	$('#gp_my_options').find('[name="map_type"]').click(function () {

		switch ($(this).val().toString()) {
			case '0':
				map.setMapTypeId(google.maps.MapTypeId.ROADMAP)
				break
			case '1':
				map.setMapTypeId(google.maps.MapTypeId.SATELLITE)
				break
			case '2':
				map.setMapTypeId(google.maps.MapTypeId.HYBRID)
				break
			case '3':
				map.setMapTypeId(google.maps.MapTypeId.TERRAIN)
				break
		}

	})

	function MapResize() {
		google.maps.event.trigger(map, 'resize')
		AutoCenter()
	}


}