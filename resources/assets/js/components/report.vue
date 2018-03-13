<template>
    <div>
        <b-container>
            <b-row class="justify-content-md-center margin-top">
                <b-col md="12">
                    <b-alert show variant="danger" v-if="notification.error">{{ notification.msg }}</b-alert>

                    <b-row v-if="!notification.error">
                        <b-col md="4">
                            <b-card v-if="UserInfo.name"
                                    :img-src="UserInfo.avatar"
                                    title="Report details">
                                <b-list-group>
                                    <b-list-group-item>Handle : <a
                                            :href="`http://codeforces.com/profile/${UserInfo.handle}`">
                                        {{ UserInfo.handle }}</a>
                                    </b-list-group-item>
                                    <b-list-group-item>Rank : {{ UserInfo.rank }} ( {{ UserInfo.rate }} )
                                    </b-list-group-item>
                                    <b-list-group-item>Contest Id : {{ ContestId }}
                                    </b-list-group-item>
                                </b-list-group>
                            </b-card>
                            <b-card class="margin-top" v-if="UserInfo.name" :title="'Top '+topusers.length+' users'"
                                    :sub-title="'of round '+ContestId+' with same rank'">

                                <b-list-group>
                                    <b-list-group-item v-for="(item, index) in topusers " :key="index">
                                        <a :href="`http://codeforces.com/profile/${item.handle}`">
                                            {{ (index + 1) + '- ' + item.handle }}</a>
                                    </b-list-group-item>
                                </b-list-group>
                            </b-card>
                        </b-col>
                        <b-col md="7">
                            <b-alert variant="info" show v-if="status != 'finished' && status != 'none'">
                                Report is under <strong>{{ status }}.</strong>
                            </b-alert>
                            <b-alert variant="warning" show v-if="status == 'finished'">
                                Warning this report is available for only 24 hours from creation time due to our limited resources.
                            </b-alert>
                            <b-card v-if="status == 'finished'" title="Report problems sheet"
                                    :sub-title="'Top '+ result.length +' common problem you didn\'t solve'">
                                <b-link :href="'/Download/'+result[0].report" class="btn btn-success mp">
                                    Download as excel sheet
                                </b-link>

                                <b-table class="card-text" :fields="fields" striped hover :items="result">
                                    <template slot="index" slot-scope="data">
                                        {{data.index + 1}}
                                    </template>
                                    <template slot="name" slot-scope="data">
                                        <span> {{data.item.info.Name }}</span>
                                    </template>
                                    <template slot="solved" slot-scope="data">
                                        <span> {{data.item.count }}</span>
                                    </template>
                                    <template slot="id" slot-scope="data">
                                        <a class="btn btn-info"
                                           :href="`http://codeforces.com/contest/${data.item.info.ContestId}/problem/${data.item.info.index}`">
                                            {{data.item.info.ContestId + data.item.info.index}}
                                        </a>
                                    </template>
                                </b-table>

                            </b-card>
                        </b-col>
                    </b-row>
                </b-col>

            </b-row>
        </b-container>
    </div>
</template>
<script>
    export default {
        name: 'report',
        data () {
            return {
                id: Window.ReportId.id,
                notification: {
                    error: false,
                    msg: 'asd'
                },
                status: "none",
                result: [],
                UserInfo: {},
                ContestId: 0,
                topusers: [],
                fields: [
                    'index',
                    {
                        key: 'id',
                        label: 'Problem',
                    },
                    {
                        label: 'Problem name',
                        key: 'name'
                    },
                    'solved'
                ]
            }
        },
        mounted(){
            this.getData();
        },
        methods: {
            getData(){
                let that = this;
                axios.get('/get/report/' + this.id).then(function (res) {
                    that.notification.error = false;
                    that.result = res.data.data;
                    that.UserInfo = res.data.user;
                    that.status = res.data.status;
                    that.ContestId = res.data.contestid;
                    that.topusers = res.data.topusers;
                }).catch(function (error) {
                    that.notification.error = true;
                    that.notification.msg = error.response.data.message;
                });
            }
        },
        computed: {}
    }
</script>