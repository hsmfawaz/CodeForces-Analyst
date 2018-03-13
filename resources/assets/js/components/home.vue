<template>
    <div>
        <b-container>
            <b-jumbotron header="CodeForces Analyst"
                         lead="simple script to increase your competitive programming skills"
                         class="c1">
                <p>
                    by comparing you with the top 10 users with same rank as you in a specific codeforces round , You will get an excel sheet with top common problems
                    you didnot solve and they solved it.
                </p>
            </b-jumbotron>
            <b-row class="justify-content-md-center margin-top">
                <b-col md="6">
                    <b-alert variant="success" v-if="id != 0" show>
                        <p v-if="status == 'queue'">We have queued your request due to many requests.</p>
                        <a class="btn btn-primary btn-block" :href="'/result/'+id">Your result will appear here</a>
                    </b-alert>
                    <b-alert variant="danger" v-if="id == 0 && submited " show>
                        You didn't participate in the selected round
                    </b-alert>
                </b-col>
            </b-row>
            <b-row class="justify-content-md-left margin-top">
                <b-col md="6">


                    <b-form @submit="onSubmit" v-if="id == 0">
                        <b-form-group id="userhandle" label="Your Handle :" label-for="handle">
                            <b-form-input id="handle" :state="errors.handle ? 'invalid' : 'valid'"
                                          type="text"
                                          v-model="form.handle" required></b-form-input>
                            <b-form-invalid-feedback v-if="errors.handle">
                                {{ errors.handle[0] }}
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group id="roundid" label="Round ID :" label-for="round">
                            <b-form-input id="round" :state="errors.round ? 'invalid' : 'valid'"
                                          type="number" v-model="form.round" required></b-form-input>
                            <b-form-invalid-feedback v-if="errors.round">
                                {{ errors.round[0] }}
                            </b-form-invalid-feedback>
                        </b-form-group>

                        <b-form-group>
                            <b-row>
                                <b-col md="6">
                                    <b-form-group label="Number of problems :" label-for="problems">
                                        <b-form-input :state="errors.problems ? 'invalid' : 'valid'"
                                                      id="problems" type="number" v-model="form.problems"
                                                      required></b-form-input>
                                        <b-form-invalid-feedback v-if="errors.problems">
                                            {{ errors.problems[0] }}
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                </b-col>
                                <b-col md="6">
                                    <b-form-group label="Number of top users :" label-for="users">
                                        <b-form-input :state="errors.users ? 'invalid' : 'valid'" id="users"
                                                      type="number" v-model="form.users"
                                                      required></b-form-input>
                                        <b-form-invalid-feedback v-if="errors.users">
                                            {{ errors.users[0] }}
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                </b-col>
                            </b-row>
                        </b-form-group>
                        <b-button type="submit" variant="primary" @click="Clicked = true" :disabled="Clicked">Create report</b-button>
                    </b-form>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>
<script>
    export default {
        name: 'app',
        data () {
            return {
                id: 0,
                status: "none",
                form: {
                    round: 948,
                    users: 10,
                    problems: 100,
                    handle: 'hsmfawaz',
                },
                errors: {}
                , Clicked: false,
                submited: false
            }
        },
        methods: {
            onSubmit (evt) {
                evt.preventDefault();
                let that = this;
                that.submited = false;
                axios.post('/set/report', this.form)
                    .then(function (response) {
                        that.Clicked = false;
                        that.id = response.data.id;
                        that.status = response.data.status;
                        that.submited = true;
                    })
                    .catch(function (error) {
                        that.Clicked = false;
                        that.errors = error.response.data.errors;
                        console.log(error.response.data.message);
                    });
            }
        }
    }
</script>