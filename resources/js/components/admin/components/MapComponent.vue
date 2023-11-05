<template>
    <LoadingContentComponent :props="loading" />
    <div class="w-full max-w-[630px] rounded-2xl bg-white">
        <div v-if="setting.autocomplete || setting.currentLocation"
            class="w-full h-12 mb-4 shadow-xs rounded-lg flex items-center gap-5 pl-[18px] pr-2.5 bg-white">
            <i class="lab lab-search-normal lab-font-size-24"></i>
            <input v-if="setting.autocomplete" id="map-autocomplete-input" type="text" placeholder="Enter a location"
                class="w-full h-full placeholder:text-lg placeholder:font-normal placeholder:font-public text-lg font-public">
            <button v-if="setting.currentLocation" id="map-current-location" type="button"
                class="flex-shrink-0 w-7 h-7 rounded flex items-center justify-center bg-primary">
                <i class="lab lab-gps-tracker text-white"></i>
            </button>
        </div>
        <div id="the-google-map" class="w-full h-[300px] rounded-xl"></div>
    </div>
</template>

<script>
import { Loader } from "google-maps";
import LoadingContentComponent from "./LoadingContentComponent";
import _ from "lodash";
import ENV from '../../../config/env';

const options = { libraries: ["places", "geometry", "drawing"] };
const loader = new Loader(ENV.GOOGLE_MAP_KEY, options);

export default {
    name: "MapComponent",
    components: { LoadingContentComponent },
    props: {
        location: Object,
        position: Function,
        setting: {
            type: Object,
            default: {
                autocomplete: {
                    type: Boolean,
                    default: true,
                    required: false
                },
                mouseEvent: {
                    type: Boolean,
                    default: true,
                    required: false
                },
                currentLocation: {
                    type: Boolean,
                    default: true,
                    required: false
                }
            }
        }
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            currentLocation: {
                lat: null,
                lng: null,
            },
            address: null,
        }
    },
    mounted: async function () {
        this.loading.isActive = true;
        if ((this.location.lat === null || this.location.lat === "") && (this.location.lng === null || this.location.lng === "")) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.currentLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        this.mainMap(this.currentLocation);
                    }, () => {
                        alert('The Geolocation service failed.');
                    }
                );
            } else {
                alert("Your browser doesn't support geolocation.");
            }
        } else {
            this.currentLocation.lat = parseFloat(this.location.lat);
            this.currentLocation.lng = parseFloat(this.location.lng);
            await this.mainMap(this.currentLocation);
        }
    },
    methods: {
        mainMap: async function (location) {
            const google = await loader.load();

            const map = new google.maps.Map(document.getElementById("the-google-map"), {
                center: location,
                zoom: 15
            });

            let markers = [];
            const marker = new google.maps.Marker({
                position: location,
                map: map
            });
            markers.push(marker);

            if (this.setting.currentLocation) {
                let currentLocationButton = document.getElementById('map-current-location');
                currentLocationButton.addEventListener("click", () => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const latLng = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude,
                                };

                                this.currentLocation = latLng;

                                for (let i = 0; i < markers.length; i++) {
                                    markers[i].setMap(null);
                                }

                                markers = [];
                                const marker = new google.maps.Marker({
                                    position: latLng,
                                    map: map
                                });
                                markers.push(marker);

                                window.setTimeout(() => {
                                    map.setZoom(15);
                                    map.setCenter(marker.getPosition());
                                }, 1000);
                                this.setPosition();
                            }, () => {
                                alert('The Geolocation service failed.');
                            }
                        );
                    } else {
                        alert("Your browser doesn't support geolocation.");
                    }
                });
            }

            if (this.setting.mouseEvent) {
                map.addListener("click", (mapsMouseEvent) => {
                    const latLng = mapsMouseEvent.latLng.toJSON();
                    this.currentLocation.lat = latLng.lat;
                    this.currentLocation.lng = latLng.lng;

                    for (let i = 0; i < markers.length; i++) {
                        markers[i].setMap(null);
                    }

                    markers = [];
                    const marker = new google.maps.Marker({
                        position: latLng,
                        map: map
                    });
                    markers.push(marker);
                    this.setPosition();
                });
            }

            if (this.setting.autocomplete) {
                let input = document.getElementById('map-autocomplete-input');
                const autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    const latLng = {
                        lat: place.geometry.location.lat(),
                        lng: place.geometry.location.lng()
                    };
                    this.currentLocation = latLng;

                    for (let i = 0; i < markers.length; i++) {
                        markers[i].setMap(null);
                    }

                    markers = [];
                    const marker = new google.maps.Marker({
                        position: latLng,
                        map: map
                    });
                    markers.push(marker);

                    window.setTimeout(() => {
                        map.setZoom(15);
                        map.setCenter(marker.getPosition());
                    }, 1000);
                    this.setPosition();
                });
            }
            this.setPosition();
            this.loading.isActive = false;
        },
        setPosition: function () {
            let other = {
                "rodeNo": null,
                "block": null,
                "area": null,
                "city": null,
                "zipCode": null,
                "state": null,
                "country": null,
            };
            const latLngLiteral = new google.maps.LatLng(this.currentLocation.lat, this.currentLocation.lng);
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ latLng: latLngLiteral }).then(res => {


                for (var i = 0; i < res.results.length; i++) {
                    for (var j = 0; j < res.results[i].address_components.length; j++) {

                        if (res.results[i].address_components[j].types[0] === "route" && other.rodeNo === null) {
                            other.rodeNo = res.results[i].address_components[j].long_name;
                        }

                        if (res.results[i].address_components[j].types[0] === "neighborhood" && res.results[i].address_components[j].types[1] === "political" && other.block === null) {
                            other.block = res.results[i].address_components[j].long_name;
                        }

                        if (res.results[i].address_components[j].types[0] === "political" && res.results[i].address_components[j].types[1] === "sublocality" && res.results[i].address_components[j].types[2] === "sublocality_level_1" && other.area === null) {
                            other.area = res.results[i].address_components[j].long_name;
                        }

                        if (res.results[i].address_components[j].types[0] === "locality" && res.results[i].address_components[j].types[1] === "political" && other.city === null) {
                            other.city = res.results[i].address_components[j].long_name;
                        }

                        for (var k = 0; k < res.results[i].address_components[j].types.length; k++) {
                            if (res.results[i].address_components[j].types[k] === "postal_code" && other.zipCode === null) {
                                other.zipCode = res.results[i].address_components[j].long_name;
                            }
                        }

                        if (res.results[i].address_components[j].types[0] === "administrative_area_level_1" && res.results[i].address_components[j].types[1] === "political" && other.state === null) {
                            other.state = res.results[i].address_components[j].long_name;
                        }


                        if (res.results[i].address_components[j].types[0] === "country" && other.country === null) {
                            other.country = res.results[i].address_components[j].long_name;
                        }


                    }
                }

                let formatted_address = "";
                _.forEach(other, (value, index) => {
                    if (value !== null && value !== "") {
                        formatted_address += value;

                        if (index !== "country") {
                            formatted_address += ",";
                        }
                        formatted_address += " ";
                    }
                });

                this.address = formatted_address;

                this.position({ address: this.address, other: other, location: this.currentLocation });
            }).catch((error) => {
                this.position({ address: this.address, other: other, location: this.currentLocation });
            });
        },
    }
}
</script>
