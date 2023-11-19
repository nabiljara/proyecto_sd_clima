<x-app-layout>

    <style>
        :root {
            --building-color: #FF9800;
            --house-color: #0288D1;
            --shop-color: #7B1FA2;
            --warehouse-color: #558B2F;
        }

        #map {
            height: 100vh;
            width: 100%;
        }

        .property {
            align-items: center;
            background-color: #ffffff;
            border-radius: 50%;
            color: #263238;
            display: flex;
            font-size: 14px;
            gap: 15px;
            height: 30px;
            justify-content: center;
            padding: 4px;
            position: relative;
            position: relative;
            transition: all 0.3s ease-out;
            width: 30px;
        }

        .property::after {
            border-left: 9px solid transparent;
            border-right: 9px solid transparent;
            border-top: 9px solid #FFFFFF;
            content: "";
            height: 0;
            left: 50%;
            position: absolute;
            top: 95%;
            transform: translate(-50%, 0);
            transition: all 0.3s ease-out;
            width: 0;
            z-index: 1;
        }

        .property .icon {
            align-items: center;
            display: flex;
            justify-content: center;
            color: #FFFFFF;
        }

        .property .icon svg {
            height: 20px;
            width: auto;
        }

        .property .details {
            display: none;
            flex-direction: column;
            flex: 1;
        }

        .property .address {
            color: #9E9E9E;
            font-size: 10px;
            margin-bottom: 10px;
            margin-top: 5px;
        }

        .property .features {
            align-items: flex-end;
            display: flex;
            flex-direction: row;
            gap: 10px;
        }

        .property .features>div {
            align-items: center;
            background: #F5F5F5;
            border-radius: 5px;
            border: 1px solid #ccc;
            display: flex;
            font-size: 10px;
            gap: 5px;
            padding: 5px;
        }

        /*
 * Property styles in highlighted state.
 */
        .property.highlight {
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 10px 10px 5px rgba(0, 0, 0, 0.2);
            height: 80px;
            padding: 8px 15px;
            width: auto;
        }

        .property.highlight::after {
            border-top: 9px solid #FFFFFF;
        }

        .property.highlight .details {
            display: flex;
        }

        .property.highlight .icon svg {
            width: 50px;
            height: 50px;
        }

        .property .bed {
            color: #FFA000;
        }

        .property .bath {
            color: #03A9F4;
        }

        .property .size {
            color: #388E3C;
        }

        /*
 * House icon colors.
 */
        .property.highlight:has(.fa-house) .icon {
            color: var(--house-color);
        }

        .property:not(.highlight):has(.fa-house) {
            background-color: var(--house-color);
        }

        .property:not(.highlight):has(.fa-house)::after {
            border-top: 9px solid var(--house-color);
        }

        /*
 * Building icon colors.
 */
        .property.highlight:has(.fa-building) .icon {
            color: var(--building-color);
        }

        .property:not(.highlight):has(.fa-building) {
            background-color: var(--building-color);
        }

        .property:not(.highlight):has(.fa-building)::after {
            border-top: 9px solid var(--building-color);
        }

        /*
 * Warehouse icon colors.
 */
        .property.highlight:has(.fa-warehouse) .icon {
            color: var(--warehouse-color);
        }

        .property:not(.highlight):has(.fa-warehouse) {
            background-color: var(--warehouse-color);
        }

        .property:not(.highlight):has(.fa-warehouse)::after {
            border-top: 9px solid var(--warehouse-color);
        }

        /*
 * Shop icon colors.
 */
        .property.highlight:has(.fa-shop) .icon {
            color: var(--shop-color);
        }

        .property:not(.highlight):has(.fa-shop) {
            background-color: var(--shop-color);
        }

        .property:not(.highlight):has(.fa-shop)::after {
            border-top: 9px solid var(--shop-color);
        }
    </style>
    <div id="map"></div>
    {{-- <script>
        function initMap() {
            let uluru = {
                lat: -25.344,
                lng: 131.036
            };
            let map = new google.maps.Map(document.getElementById("map"), {
                zoom: 4,
                center: uluru,
            });
            let marker = new google.maps.Marker({
                position: uluru,
                map: map
            });
        }
    </script> --}}
    <script>
        async function initMap() {
            // Request needed libraries.
            const {
                Map
            } = await google.maps.importLibrary("maps");
            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");
            const center = {
                lat: 37.43238031167444,
                lng: -122.16795397128632
            };
            const map = new Map(document.getElementById("map"), {
                zoom: 11,
                center,
                mapId: "4504f8b37365c3d0",
            });

            for (const property of properties) {
                const AdvancedMarkerElement = new google.maps.marker.AdvancedMarkerElement({
                    map,
                    content: buildContent(property),
                    position: property.position,
                    title: property.description,
                });

                AdvancedMarkerElement.addListener("click", () => {
                    toggleHighlight(AdvancedMarkerElement, property);
                });
            }
        }

        function toggleHighlight(markerView, property) {
            if (markerView.content.classList.contains("highlight")) {
                markerView.content.classList.remove("highlight");
                markerView.zIndex = null;
            } else {
                markerView.content.classList.add("highlight");
                markerView.zIndex = 1;
            }
        }

        function buildContent(property) {
            const content = document.createElement("div");

            content.classList.add("property");
            content.innerHTML = `
    <div class="icon">
        <i aria-hidden="true" class="fa fa-icon fa-${property.type}" title="${property.type}"></i>
        <span class="fa-sr-only">${property.type}</span>
    </div>
    <div class="details">
        <div class="price">${property.price}</div>
        <div class="address">${property.address}</div>
        <div class="features">
        <div>
            <i aria-hidden="true" class="fa fa-bed fa-lg bed" title="bedroom"></i>
            <span class="fa-sr-only">bedroom</span>
            <span>${property.bed}</span>
        </div>
        <div>
            <i aria-hidden="true" class="fa fa-bath fa-lg bath" title="bathroom"></i>
            <span class="fa-sr-only">bathroom</span>
            <span>${property.bath}</span>
        </div>
        <div>
            <i aria-hidden="true" class="fa fa-ruler fa-lg size" title="size"></i>
            <span class="fa-sr-only">size</span>
            <span>${property.size} ft<sup>2</sup></span>
        </div>
        </div>
    </div>
    `;
            return content;
        }

        const properties = [{
                address: "215 Emily St, MountainView, CA",
                description: "Single family house with modern design",
                price: "$ 3,889,000",
                type: "home",
                bed: 5,
                bath: 4.5,
                size: 300,
                position: {
                    lat: 37.50024109655184,
                    lng: -122.28528451834352,
                },
            },
            {
                address: "108 Squirrel Ln &#128063;, Menlo Park, CA",
                description: "Townhouse with friendly neighbors",
                price: "$ 3,050,000",
                type: "building",
                bed: 4,
                bath: 3,
                size: 200,
                position: {
                    lat: 37.44440882321596,
                    lng: -122.2160620727,
                },
            },
            {
                address: "100 Chris St, Portola Valley, CA",
                description: "Spacious warehouse great for small business",
                price: "$ 3,125,000",
                type: "warehouse",
                bed: 4,
                bath: 4,
                size: 800,
                position: {
                    lat: 37.39561833718522,
                    lng: -122.21855116258479,
                },
            },
            {
                address: "98 Aleh Ave, Palo Alto, CA",
                description: "A lovely store on busy road",
                price: "$ 4,225,000",
                type: "store-alt",
                bed: 2,
                bath: 1,
                size: 210,
                position: {
                    lat: 37.423928529779644,
                    lng: -122.1087629822001,
                },
            },
            {
                address: "2117 Su St, MountainView, CA",
                description: "Single family house near golf club",
                price: "$ 1,700,000",
                type: "home",
                bed: 4,
                bath: 3,
                size: 200,
                position: {
                    lat: 37.40578635332598,
                    lng: -122.15043378466069,
                },
            },
            {
                address: "197 Alicia Dr, Santa Clara, CA",
                description: "Multifloor large warehouse",
                price: "$ 5,000,000",
                type: "warehouse",
                bed: 5,
                bath: 4,
                size: 700,
                position: {
                    lat: 37.36399747905774,
                    lng: -122.10465384268522,
                },
            },
            {
                address: "700 Jose Ave, Sunnyvale, CA",
                description: "3 storey townhouse with 2 car garage",
                price: "$ 3,850,000",
                type: "building",
                bed: 4,
                bath: 4,
                size: 600,
                position: {
                    lat: 37.38343706184458,
                    lng: -122.02340436985183,
                },
            },
            {
                address: "868 Will Ct, Cupertino, CA",
                description: "Single family house in great school zone",
                price: "$ 2,500,000",
                type: "home",
                bed: 3,
                bath: 2,
                size: 100,
                position: {
                    lat: 37.34576403052,
                    lng: -122.04455090047453,
                },
            },
            {
                address: "655 Haylee St, Santa Clara, CA",
                description: "2 storey store with large storage room",
                price: "$ 2,500,000",
                type: "store-alt",
                bed: 3,
                bath: 2,
                size: 450,
                position: {
                    lat: 37.362863347890716,
                    lng: -121.97802139023555,
                },
            },
            {
                address: "2019 Natasha Dr, San Jose, CA",
                description: "Single family house",
                price: "$ 2,325,000",
                type: "home",
                bed: 4,
                bath: 3.5,
                size: 500,
                position: {
                    lat: 37.41391636421949,
                    lng: -121.94592071575907,
                },
            },
        ];
    </script>
    <!-- prettier-ignore -->
      <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('MAPS_GOOGLE_MAPS_ACCESS_TOKEN') }}&callback=initMap"></script>
    <script async defer src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script async defer src="https://use.fontawesome.com/releases/v6.2.0/js/all.js"></script>
</x-app-layout>

{{-- 
<script>
  fetch('/json-key-feed')
      .then(a => {
          return a.json();
      })
      .then(result => {
          console.log(result.api_key)
          
          const url = 'http://localhost:3000/apiv1/estaciones';
          const data = {
              api_key: result.api_key,
              x_api_secret:  result.x_api_secret
          };
          fetch(url, {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json'
                  },
                  body: JSON.stringify(data)
              })
              .then(response => response.json())
              .then(data => {
                  //console.log('Respuesta del servidor:', data);

                 var componente = "<x-maps-google :markers=["; 

                  data.stations.forEach((station) => 
                    componente += "[\'lat\' =>" + station.latitude + ", \'long\' =>" + station.longitude + ", \'title\' =>\'" + station.station_name +"\'],"; 
                  );

                  componente += '] :centerToBoundsCenter=\"true\" :zoomLevel=\"7\">';

                  componente += '</x-maps-google>';
                  console.log(componente);
                  document.getElementById("map").innerHtml =componente;
                      
              })
              .catch(error => {
                  console.error('Error en la solicitud:', error);
              });
      });
</script> --}}
