<template>
    <div>
        <div class="alert alert-info">
            <p><strong>{{ lang["hi"] }}!</strong></p>
            <p>{{ lang["join_or_wait_for_joins"] }}</p>
        </div>

        <div class="row">
            <div class="table-responsive">
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
                        <td>{{ workout.starts_at }}</td>
                        <td>2</td>
                        <td>{{ workout.minutes }}:{{ workout.seconds }}/km</td>
                        <td>{{ workout.distance }} km</td>
                        <td>{{ parseFloat(workout.distance_in_km).toFixed(2) }} km</td>
                        <td>
                            <button v-if="myWorkout.id != workout.id" @click="joinWorkout(workout.id)" class="btn btn-success">{{ lang["workout.join"]
                                }}
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
    export default {
        mounted() {
            console.log('Listing Workouts');

            this.myLocation = JSON.parse(localStorage.getItem('myLocation'))
            this.myWorkout = JSON.parse(localStorage.getItem('myWorkout'))

            if (this.myLocation !== null) {
                this.getWorkouts()
            }
        },
        data() {
            return {
                lang: window.lang,
                myLocation: {},
                myWorkout: {},
                workouts: []
            }
        },
        methods: {
            getWorkouts() {
                axios.get('/api/workout/' + this.myLocation.latitude + '/' + this.myLocation.longitude)
                    .then(response => {
                        this.workouts = response.data

                        this.workouts.forEach(workout => {
                            this.formatTempo(workout)
                        })
                    })
            },
            formatTempo(workout) {
                workout.minutes = Math.round(workout.tempo / 60);
                workout.seconds = workout.tempo - (Math.round(workout.tempo / 60) * 60);

                if (workout.seconds < 10) workout.seconds = '0' + workout.seconds;
            },
            joinWorkout(workout_id) {
                console.log(window.user_id + ' wants to join ' + workout_id);
            }
        }
    }
</script>