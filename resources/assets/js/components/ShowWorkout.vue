<template>
    <div>
        <table class="table table-striped">
            <tr>
                <td>{{ lang["workout.start"] }}:</td>
                <td>{{ workout.humanDate }} {{ workout.humanTime }}</td>
            </tr>
            <tr>
                <td>{{ lang["workout.attendees"] }}:</td>
                <td>{{ workout.attendees.length + 1 }}</td>
            </tr>
            <tr>
                <td>{{ lang["workout.tempo"] }}:</td>
                <td>{{ workout.minutes }}:{{ workout.seconds }}/km</td>
            </tr>
            <tr>
                <td>{{ lang["workout.distance"] }}:</td>
                <td>{{ workout.distance }} km</td>
            </tr>
            <tr>
                <td>{{ lang["workout.join"] }}</td>
                <td>
                    <button v-if="myWorkout.id != workout.id && !workout.has_joined" @click="joinWorkout(workout.id)" class="btn btn-success">
                        {{ lang["workout.join"] }}
                    </button>
                    <button v-if="myWorkout.id != workout.id && workout.has_joined" @click="leaveWorkout(workout.id)" class="btn btn-danger">
                        {{ lang["workout.leave"] }}
                    </button>
                    <span v-if="myWorkout.id == workout.id">{{ lang["workout.your_workout"] }}</span>
                </td>
            </tr>

            <tr>
                <td>{{ lang["workout.proposed_meeting_place"] }}:</td>
                <td>{{ lang["workout.meeting_place_bus_station"] }} {{ meetingPlace }}</td>
            </tr>
        </table>

    </div>

</template>

<script>

    export default {

        props: ['id'],

        mounted() {

            setTimeout(() => {
                this.getWorkout();

                this.myWorkout = {
                    id: window.user_workout
                }
            }, 1);

        },
        data() {
            return {
                lang: window.lang,
                myWorkout: {},
                workout: {
                    attendees: []
                },

                map: null,
                service: null,
                infowindow: null,
                meetingPlace: '',
            }
        },
        methods: {
            getWorkout() {
                axios.get('/api/workout/' + this.id)
                    .then(response => {

                        let workout = response.data;
                        formatTempo(workout)

                        workout.has_joined = false
                        workout.attendees.forEach(attendee => {
                            workout.has_joined = attendee.id == window.user_id
                        })

                        this.workout = workout

                        this.mapInitialize(workout.latitude, workout.longitude);
                    })
            },
            joinWorkout(workout_id) {

                axios.post('/api/workout/' + workout_id + '/join', {id: window.user_id})
                    .then(response => {
                        this.getWorkout();
                    }).catch(error => {

                });
            },
            leaveWorkout(workout_id) {

                axios.post('/api/workout/' + workout_id + '/leave', {id: window.user_id})
                    .then(response => {
                        this.getWorkout();
                    }).catch(error => {

                });
            },


            mapInitialize(lat, lng) {

                console.log(lat);
                console.log(lng);

                let origin = new google.maps.LatLng(lat, lng);


                this.map = new google.maps.Map(document.getElementById('map'), {
                    mapTypeId: google.maps.MapTypeId.HYBRID,
                    center: origin,
                    zoom: 15
                });

                let request = {
                    location: origin,
                    radius: 2500,
                    types: [
                        //'train_station',
                        'bus_station',
                        //'subway_station',
                        //'transit_station'
                    ]
                };
                //infowindow = new google.maps.InfoWindow();
                this.service = new google.maps.places.PlacesService(this.map);
                this.service.search(request, this.mapCallback);
            },

            mapCallback(results, status) {

                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    for (let i = 0; i < 1; i++) {
                        this.mapCreateMarker(results[i]);

                        this.meetingPlace = results[i].name
                    }
                }
            },

            mapCreateMarker(place) {

                console.log(place)

                let placeLoc = place.geometry.location;
                let marker = new google.maps.Marker({
                    map: this.map,
                    position: place.geometry.location
                });


                let content = '<strong style="font-size:1.2em">' + place.name + '</strong>' +
                    '<br/><strong>Latitude:</strong>' + placeLoc.lat() +
                    '<br/><strong>Longitude:</strong>' + placeLoc.lng() +
                    '<br/><strong>Type:</strong>' + place.types[0] +
                    '<br/><strong>Rating:</strong>' + (place.rating || 'n/a');

                let more_content = '<img src="http://googleio2009-map.googlecode.com/svn-history/r2/trunk/app/images/loading.gif"/>';

                //make a request for further details
                this.service.getDetails({reference: place.reference}, function (place, status) {
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        more_content = '<hr/><strong><a href="' + place.url + '" target="details">Details</a>';

                        if (place.website) {
                            more_content += '<br/><br/><strong><a href="' + place.website + '" target="details">' + place.website + '</a>';
                        }
                    }
                });


                google.maps.event.addListener(marker, 'click', function () {

                    this.infowindow.setContent(content + more_content);
                    this.infowindow.open(this.map, this);
                });
            },
        }
    }
</script>
