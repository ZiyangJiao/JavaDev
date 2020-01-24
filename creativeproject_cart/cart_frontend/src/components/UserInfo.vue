<template>
    <div class="col align-self-center">
        <h5>
            <b-badge variant="light">Welcome</b-badge>
            <b-button variant="outline-danger" v-on:click="logOut($event)">Log out</b-button>
        </h5>
    </div>
</template>

<script>
    // import axios from "axios";
    import bus from "./../bus";

    export default {
        data() {
            return {
                username: ""
            };
        },
        methods: {
            logOut(event) {
                if (event) {
                    event.preventDefault();
                }
                let user = {
                    username: localStorage.userName
                };
                localStorage.clear();
                console.log(user);
                this.$http
                    .post("/logout", user)
                    .then(response => {
                        bus.$emit("Go_to_login");
                    })
                    .catch(error => console.log(error));

            }
        }
    };
</script>

<style lang="scss" scoped>
    .underline {
        text-decoration: underline;
    }
</style>
