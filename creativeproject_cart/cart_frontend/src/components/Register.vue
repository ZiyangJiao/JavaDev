<template>
    <div>
        <b-form @submit="onSubmit" @reset="onReset" v-if="show">
            <b-form-group id="input-group-2" label="Username:" label-for="input-2">
                <b-form-input
                        type="text"
                        id="input-1"
                        v-model="username"
                        required
                        placeholder="Enter name"
                ></b-form-input>
            </b-form-group>

            <b-form-group id="input-group-3" label="Password:" label-for="input-3">
                <b-form-input
                        type="password"
                        id="input-2"
                        v-model="password"
                        required
                        placeholder="Enter password"
                ></b-form-input>
            </b-form-group>

            <b-form-group id="input-group-4" label="Confirm password:" label-for="input-3">
                <b-form-input
                        type="password"
                        id="input-3"
                        v-model="password2"
                        required
                        placeholder="Enter password again to confirm"
                ></b-form-input>
            </b-form-group>

            <b-button type="submit" variant="primary">Submit</b-button>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <b-button type="reset" variant="primary">Back to Login</b-button>
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
                password2: '',
                show: true
            }
        },
        methods: {
            validate: function () {
                return this.password === this.password2;
            },

            onSubmit(event) {
                if (event) {
                    event.preventDefault();
                }
                // Verify if both passwords match
                if (!(this.validate())) {
                    this.$bvModal.msgBoxOk('Passwords do not match. Please retry');
                    return;
                }
                let info = {
                    username: this.username,
                    password: this.password
                };
                this.$http
                    .post("/register", info)
                    .then(res => {
                        if (res.status === 200) {
                            localStorage.userName = info.username;
                            bus.$emit("Login_success");
                        } else {
                            this.$bvModal.msgBoxOk('[Error] Failed to register');
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        this.$bvModal.msgBoxOk('[Error] Failed to register');
                    });
            },

            onReset(evt) {
                bus.$emit("Go_to_login");
            },
        }
    }
</script>