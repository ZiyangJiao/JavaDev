<template>
    <div class="container">
        <div class="row vertical-centre justify-content-center mt-50">
            <div class="col-md-6 mx-auto" id="root">
                <!--Show login-->
                <div v-if="pageStatus === 'login'">
                    <Login></Login>
                </div>
                <!--Show register-->
                <div v-else-if="pageStatus === 'register'">
                    <Register></Register>
                </div>
                <!--Show list-->
                <div v-else-if="pageStatus === 'list'">
                    <UserInfo></UserInfo>
                    <AddItem></AddItem>
                    <ListItem></ListItem>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AddItem from "./components/AddItem.vue";
    import ListItem from "./components/ListItem.vue";
    import Login from "./components/Login.vue";
    import UserInfo from "./components/UserInfo.vue";
    import Register from "./components/Register.vue";
    import bus from "./bus";

    export default {
        name: "app",
        components: {AddItem, ListItem, Login, UserInfo, Register},
        data() {
            return {
                pageStatus: (localStorage.userName !== undefined && localStorage.userName !== null) ? "list" : "login"  // login: Show login; register: Show register; list: show list
            };
        },

        created() {
            if (localStorage.hasOwnProperty("userName")) {
                this.pageStatus = "list";
            }

            // Login successfully
            bus.$on("Login_success", $event => {
                this.pageStatus = "list";  // After logging in, show list
            });

            // Show login
            bus.$on("Go_to_login", $event => {
                this.pageStatus = "login";  // After logging out / choosing to login, show login
            });

            bus.$on("Go_to_register", $event => {
                this.pageStatus = "register";
            });
        },

    };


</script>

<style lang="scss">
    @import "node_modules/bootstrap/scss/bootstrap";
    @import "node_modules/bootstrap-vue/src/index.scss";

    .vertical-centre {
        min-height: 100%;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .todo__row {
        width: 400px;
    }
</style>
