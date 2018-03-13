<template>
    <div class="container-fluid" style="background: #563d7c ">
        <b-container class="container">
            <b-navbar toggleable="md" type="dark">

                <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>

                <b-navbar-brand href="/">CodeForces Analyst</b-navbar-brand>

                <b-collapse is-nav id="nav_collapse">

                    <b-navbar-nav>
                        <b-nav-item href="/">Home</b-nav-item>
                        <b-nav-item href="/latest">Latest reports</b-nav-item>
                        <!--<b-nav-item href="/contributors">Contributors</b-nav-item>-->
                        <b-nav-item href="mailto:firecold72@gmail.com">Contact me</b-nav-item>
                    </b-navbar-nav>
                </b-collapse>
            </b-navbar>
        </b-container>
    </div>
</template>
<script>
    export default {
        name: 'navbar',
        data () {
            return {}
        }
    }
</script>