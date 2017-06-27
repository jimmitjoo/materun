<template>
    <div>
        <div class="alert alert-info">
            <p><strong>{{ lang["hi"] }}!</strong></p>
            <p>{{ lang["join_or_wait_for_joins"] }}</p>
        </div>

        <div class="alert alert-danger" v-if="errors.any()">
            <p><strong>{{ lang["validation.whoops_something_went_wrong"] }}</strong></p>
            <ul>
                <li v-for="(key, value, index) in errors.errors">{{ lang[key] }}</li>
            </ul>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <label>{{ lang["workout.how_far_can_you_travel"] }}</label>
            </div>
            <div class="col-xs-7">
                <div class="input-group">
                    <input type="number" class="form-control" v-model="radie">
                    <div class="input-group-addon">km</div>
                </div>
            </div>
            <div class="col-xs-5">
                <button class="btn btn-default" @click="getWorkouts">Filtrera</button>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-xs-12">
                <table class="table">
                    <thead>
                    <th>{{ lang["workout.start"] }}</th>
                    <th><span class="glyphicon glyphicon-user"></span></th>
                    <th><span class="glyphicon glyphicon-time"></span></th>
                    <th>{{ lang["workout.distance"] }}</th>
                    <th>{{ lang["workout.how_far_away"] }}</th>
                    <th>{{ lang["workout.join"] }}</th>
                    </thead>
                    <tr v-for="workout in workouts">
                        <td><a :href="'/workout/' + workout.id">{{ workout.starts_at }}</a></td>
                        <td>{{ workout.attendees.length + 1 }}</td>
                        <td>{{ workout.minutes }}:{{ workout.seconds }}/km</td>
                        <td>{{ workout.distance }} km</td>
                        <td>{{ parseFloat(workout.distance_in_km).toFixed(2) }} km</td>
                        <td>
                            <button v-if="myWorkout.id != workout.id && !workout.has_joined"
                                    @click="joinWorkout(workout.id)" class="btn btn-success">
                                {{ lang["workout.join"] }}
                            </button>
                            <button v-if="myWorkout.id != workout.id && workout.has_joined"
                                    @click="leaveWorkout(workout.id)" class="btn btn-danger">
                                {{ lang["workout.leave"] }}
                            </button>
                            <span v-if="myWorkout.id == workout.id">{{ lang["workout.your_workout"] }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import Errors from '../form/errors';

    export default {
        mounted() {

            this.myLocation = JSON.parse(localStorage.getItem('myLocation'));

            setTimeout(() => {
                this.myWorkout = {
                    id: window.user_workout
                }

                if (this.myLocation !== null) {
                    this.getWorkouts()
                }
            }, 1);


        },
        data() {
            return {
                lang: window.lang,
                myLocation: {},
                myWorkout: {},
                workouts: [],
                errors: new Errors(),
                firstLoad: true,
                radie: 25
            }
        },
        methods: {
            getWorkouts() {

                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(position => {
                        this.myLocation.latitude = position.coords.latitude
                        this.myLocation.longitude = position.coords.longitude

                        localStorage.setItem('myLocation', JSON.stringify({
                            latitude: this.myLocation.latitude,
                            longitude: this.myLocation.longitude
                        }))
                    });
                }

                axios.get('/api/workout/' + this.myLocation.latitude + '/' + this.myLocation.longitude + '/' + this.radie)
                    .then(response => {
                        this.workouts = response.data

                        this.workouts.forEach(workout => {
                            formatTempo(workout)

                            workout.has_joined = false
                            workout.attendees.forEach(attendee => {
                                workout.has_joined = attendee.id == window.user_id
                            })
                        })
                    })
            },
            joinWorkout(workout_id) {

                axios.post('/api/workout/' + workout_id + '/join', {id: window.user_id})
                    .then(response => {
                        this.getWorkouts();
                    }).catch(error => {
                    this.errors.errors = error.response.data
                });
            },
            leaveWorkout(workout_id) {

                axios.post('/api/workout/' + workout_id + '/leave', {id: window.user_id})
                    .then(response => {
                        this.getWorkouts();
                    }).catch(error => {

                });
            }

        }
    }
</script>