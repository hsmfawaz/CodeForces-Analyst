<template>
    <div>
        <b-container>
            <b-row class="margin-top justify-content-md-center">
                <b-col md="8">
                    <b-card title="Top Latest 20 reports">
                        <b-table class="card-text" :fields="fields" striped hover :items="items">
                            <template slot="index" slot-scope="data">
                                {{data.index + 1}}
                            </template>
                            <template slot="id" slot-scope="data">
                                <a
                                   :href="`http://codeforces.com/profile/${data.item.handle}`">
                                    {{data.item.handle}}
                                </a>
                            </template>
                            <template slot="contest" slot-scope="data">
                                <a
                                   :href="`http://codeforces.com/contest/${data.item.contestid}`">
                                    {{data.item.contestid}}
                                </a>
                            </template>
                            <template slot="link" slot-scope="data">
                                <a class="btn btn-info"
                                   :href="`/result/${data.item.id}`">
                                    View
                                </a>
                            </template>
                        </b-table>
                    </b-card>
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
                items: Window.LatestItem,
                fields: [
                    'index',
                    {
                        key: 'id',
                        label: 'Handle',
                    },
                    {
                        label: 'Contest id',
                        key: 'contest'
                    },
                    {
                        label: 'View',
                        key: 'link'
                    }
                ]
            }
        },
        methods: {}
    }
</script>