<template>
    <form v-on:submit.prevent="submitWorkout">
        <div class="form-group">
            <label>{{ lang["workout.expected_tempo"] }}</label>
            <div class="row">
                <div class="col-xs-5">
                    <div class="input-group">
                    <select class="form-control" v-model="workout.tempoMinute">
                        <option :selected="minutes == workout.tempoMinute" :value="minutes"
                                v-for="minutes in tempo.minutes">{{ minutes }}
                        </option>
                    </select>
                    <div class="input-group-addon">{{ lang["minute_short"] }}</div>
                    </div>
                </div>
                <div class="col-xs-5">
                    <div class="input-group">

                    <select class="form-control" v-model="workout.tempoSeconds">
                        <option :selected="seconds == workout.tempoSeconds" :value="seconds"
                                v-for="seconds in tempo.seconds">{{ seconds }}
                        </option>
                    </select>
                        <div class="input-group-addon">{{ lang["second_short"] }}</div>

                    </div>
                </div>
                <div class="col-xs-2">
                    /km
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>{{ lang["workout.expected_distance"] }}</label>

            <div class="input-group">

                <select class="form-control" v-model="workout.distance">
                    <option :selected="runDistance == distance" :value="runDistance"
                            v-for="runDistance in distance">{{ runDistance }}
                    </option>
                </select>
                <div class="input-group-addon">km</div>

            </div>
        </div>
        <div class="form-group">
            <label>{{ lang["workout.when"] }}</label>

            <div class="row">
                <div class="col-xs-6">
                    <input type="date" class="form-control" v-model="workout.date" :value="workout.date">
                </div>
                <div class="col-xs-6">
                    <div class="input-group">
                        <div class="input-group-addon">{{ lang["time_prefix"] }}</div>
                        <select class="form-control" v-model="workout.hour">
                            <option :selected="hour == workout.hour" :value="hour" v-for="hour in hours">{{ hour }}
                            </option>
                        </select>
                        <div class="input-group-addon">:00</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <span v-if="workout.latitude != null">{{ lang["found_your_location"] }}</span>
            <span v-if="findingLocation">{{ lang["loading"] }}</span>
            <button v-if="!workout.latitude" :disabled="findingLocation" type="button" @click="getMyLocation" class="btn btn-default">
                {{ lang["find_my_location"] }}
            </button>
        </div>
        <button :disabled="workout.latitude == null || workout.longitude == null"
                class="btn btn-primary">{{ lang["workout.find_people_to_run_with"] }}
        </button>

        <ul>
            <li>Tempo: {{ (workout.tempoMinute * 60) + workout.tempoSeconds }}</li>
            <li>Distance: {{ workout.distance }}km</li>
            <li>Date: {{ workout.date }}</li>
            <li>Time: {{ workout.hour }}:00</li>
            <li>Lat: {{ workout.latitude }}</li>
            <li>Long: {{ workout.longitude }}</li>
        </ul>

    </form>

</template>

<script>
    export default {
        mounted() {
            let date = new Date();
            let month = (date.getMonth() + 1 < 10) ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
            let day = (date.getDate() < 10) ? '0' + date.getDate() : date.getDate();
            this.workout.date = date.getFullYear() + '-' + month + '-' + day
            this.workout.hour = date.getHours() + 2
        },
        data() {
            return {
                lang: window.lang,
                workout: {
                    tempoMinute: 5,
                    tempoSeconds: 0,
                    distance: 5,
                    date: null,
                    hour: null,
                    latitude: null,
                    longitude: null
                },
                getLocationAutomatically: true,
                findingLocation: false,
                tempo: {
                    minutes: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    seconds: [0, 15, 30, 45]
                },
                distance: [5, 10, 15, 20, 25, 30, 40, 50, 75, 100],
                hours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]
            }
        },
        methods: {
            getMyLocation() {
                this.findingLocation = true
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(position => {
                        this.workout.latitude = position.coords.latitude
                        this.workout.longitude = position.coords.longitude
                        this.findingLocation = false

                        localStorage.setItem('myLocation', JSON.stringify({
                            latitude: this.workout.latitude,
                            longitude: this.workout.longitude
                        }))
                    });
                }
            },

            submitWorkout() {

                let postData = {
                    tempo: (this.workout.tempoMinute * 60) + this.workout.tempoSeconds,
                    distance: this.workout.distance,
                    latitude: this.workout.latitude,
                    longitude: this.workout.longitude,
                    starting: this.workout.date + ' ' + this.workout.hour + ':00:00'
                };

                axios.post('/api/workout', postData)
                    .then(response => {
                        console.log(response)

                        localStorage.setItem('myWorkout', JSON.stringify({
                            id: response.data.id
                        }))

                        window.location.href = "/list";
                    }).catch(error => {
                        console.log(error)
                    });
            }
        }
    }
</script>
