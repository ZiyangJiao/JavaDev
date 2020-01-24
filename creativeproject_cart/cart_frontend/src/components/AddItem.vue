<template>
    <div class="col align-self-center">
        <h3 class="pb-5 text-left underline">Create shopping list</h3>
        <form class="sign-in" @submit.prevent>
            <div class="form-group item__row">
                <input
                        type="text"
                        class="form-control"
                        @keypress="typing=true"
                        placeholder="What item do you want buy?"
                        v-model="name"
                />
                <input
                        type="number"
                        class="form-control"
                        @keypress="typing=true"
                        placeholder="How many?"
                        v-model="quantity"
                />
                <div class="input-group-append">
                    <div class="input-group-text">
                            <span
                                    class="input-group-addon addon-left"
                                    title="Click to add"
                                    v-on:click="addItem($event)"
                            >
                +Add
              </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
    // import axios from "axios";
    import bus from "./../bus";

    export default {
        data() {
            return {
                name: "",
                quantity: "",
                typing: false
            };
        },
        methods: {
            addItem(event) {
                if (event) {
                    event.preventDefault();
                }
                let item = {
                    name: this.name,
                    quantity: this.quantity,
                    complete: false,
                    username: localStorage.userName
                };
                console.log(item);
                this.$http
                    .post("/", item)
                    .then(response => {
                        this.clearItem();
                        this.refreshItem();
                        this.typing = false;
                    })
                    .catch(error => console.log(error));

            },

            refreshItem() {
                this.name = "";
                this.quantity = "";
            },

            clearItem() {
                bus.$emit("refreshItem");
            }
        }
    };
</script>
<style lang="scss" scoped>
    .underline {
        text-decoration: underline;
    }
</style>