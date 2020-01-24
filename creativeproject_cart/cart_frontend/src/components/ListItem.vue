<template>
    <div v-bind:show="items.length>0" class="col align-self-center">
        <div class="form-row align-items-center" v-for="item in items" v-bind:key="item.id">
            <div class="col-auto my-1">
                <div class="input-group mb-3 item__row">
                    <div class="input-group-prepend">
            <span class="input-group-text">
              <input
                      type="checkbox"
                      v-model="item.complete"
                      :checked="item.complete"
                      :value="item.complete"
                      v-on:change="alterItem(item)"
                      title="Mark as done?"
              />
            </span>
                    </div>
                    <input
                            type="text"
                            class="form-control"
                            :class="item.complete?'item__complete':''"
                            v-model="item.name"
                            @keypress="item.editing=true"
                            @keyup.enter="alterItem(item)"
                    />
                    <input
                            type="text"
                            class="form-control"
                            :class="item.complete?'item__complete':''"
                            v-model="item.quantity"
                            @keypress="item.editing=true"
                            @keyup.enter="alterItem(item)"
                    />
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span
                                    class="input-group-addon addon-left"
                                    title="Delete item?"
                                    v-on:click="deleteItem(item._id)"
                            >
                X
              </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
                class="alert alert-primary item__row"
                v-show="items.length==0 && loading"
        >Hardest worker in the room. No more items now you can rest. ;)
        </div>
    </div>
</template>
<script>
    //import axios from "axios";
    import bus from "./../bus";

    export default {
        data() {
            return {
                items: [],
                loading: false
            };
        },
        created: function () {
            this.getItem();
            this.listenToEvents();
        },
        watch: {
            $route: function () {
                let self = this;
                self.loading = false;
                self.fetchData().then(function () {
                    self.loading = true;
                });
            }
        },
        methods: {
            getItem() {
                this.$http.get(`/${localStorage.userName}`).then(response => {
                    this.items = response.data;
                });
            },

            alterItem(item) {
                let id = item._id;
                this.$http
                    .put(`/${id}`, item)
                    .then(response => console.log(response))
                    .catch(error => console.log(error));
            },

            deleteItem(id) {
                this.$http
                    .delete(`/${id}`)
                    .then(response => this.getItem());
            },

            listenToEvents() {
                bus.$on("refreshItem", $event => { //matched with Additem
                    this.getItem();
                });
            }


        }
    }
</script>
<style lang="scss" scoped>
    .item__complete {
        text-decoration: line-through !important;
    }

    .no_border_left_right {
        border-left: 0px;
        border-right: 0px;
    }

    .flat_form {
        border-radius: 0px;
    }

    .mrb-10 {
        margin-bottom: 10px;
    }

    .addon-left {
        background-color: none !important;
        border-left: 0px !important;
        cursor: pointer !important;
    }

    .addon-right {
        background-color: none !important;
        border-right: 0px !important;
    }
</style>