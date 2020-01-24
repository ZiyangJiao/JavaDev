<template>
    <div>
        <b-form @submit="onSubmit" @reset="onReset" v-if="show">
            <b-form-group id="input-group-2" label="Username:" label-for="input-2">
                <b-form-input
                        type="text"
                        id="input-2"
                        v-model="username"
                        required
                        placeholder="Enter name"
                ></b-form-input>
            </b-form-group>

            <b-form-group id="input-group-3" label="Password:" label-for="input-3">
                <b-form-input
                        type="password"
                        id="input-3"
                        v-model="password"
                        required
                        placeholder="Enter password"
                ></b-form-input>
            </b-form-group>

            <b-button type="submit" variant="primary">Login</b-button>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--Some whitespace here-->
            <b-button type="reset" variant="primary">Register</b-button>
        </b-form>
    </div>
</template>

<script>
    import bus from "./../bus";

    export default {
        data() {
            return {
                username: '',
                password: '',
                show: true
            }
        },
        methods: {
            onSubmit(evt) {
                evt.preventDefault()
                // alert(JSON.stringify(this.form))
                let info = {
                    username: this.username,
                    password: this.password
                };
                console.log(info.username);
                this.$http
                    .post("/login", info)
                    .then(res => {
                        if (res.status === 200) {
                            localStorage.userName = info.username;
                            console.log(localStorage.userName);
                            bus.$emit("Login_success");
                        } else {
                            console.log('login_else');
                            this.$bvModal.msgBoxOk('username or password error');
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        this.$bvModal.msgBoxOk('username or password error');
                    });

            },
            onReset(evt) {
                bus.$emit("Go_to_register");
            },
        }
    }
</script>