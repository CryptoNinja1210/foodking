<template>
    <section class="pt-5 sm:py-6">
        <div class="container max-w-3xl px-0 sm:px-4">
            <div class="sm:rounded-xl sm:shadow-xs bg-white">
                <div class="swiper branch-swiper p-2.5 border-b border-gray-200" v-if="branches.length > 1">
                    <nav class="swiper-wrapper">
                        <Carousel :settings="branchSettings" :breakpoints="branchBreakpoints">
                            <slide v-for="branch in branches" :key="branch" class="branch-navs">
                                <button @click="branchId(branch.id)"
                                    class="w-full overflow-hidden branch-margin-right py-2 px-3 rounded-lg text-center text-sm whitespace-nowrap text-heading bg-[#F7F7FC] transition hover:text-primary hover:bg-primary/5"
                                    :class="branch.id === defaultProps.branch ? 'active' : ''">
                                    {{ branch.name }}
                                </button>
                            </slide>
                        </Carousel>
                    </nav>
                </div>

                <ul class="chat-list frontend">
                    <div v-if="userMessages.length > 0" v-for="message in userMessages" :key="message">
                        <li class="chat-item chat-admin" v-if="message.user_id !== user.id">
                            <img class="chat-avatar" :src="message.user_image" alt="avatar">
                            <div class="chat-group">
                                <div class="chat-group-text" v-if="message.text && message.image">
                                    <p class="chat-text">{{ message.text }}</p>
                                    <p class="chat-text"><img class="w-full max-w-xs" :src="message.image" alt="images"></p>
                                </div>
                                <div class="chat-group-text" v-else>
                                    <p class="chat-text" v-if="message.text">{{ message.text }}</p>
                                    <p class="chat-text" v-else><img class="w-full max-w-xs" :src="message.image"
                                            alt="images"></p>
                                </div>
                                <div class="chat-group-meta">
                                    <span class="chat-meta">{{ $t("label.reply_from") }} {{ message.user_name }}</span>
                                    <span class="chat-meta">{{ message.reply_at }}</span>
                                </div>
                            </div>
                        </li>
                        <li class="chat-item chat-user" v-else>
                            <img class="chat-avatar" :src="message.user_image" alt="avatar">
                            <div class="chat-group">
                                <div class="chat-group-text" v-if="message.text && message.image">
                                    <p class="chat-text">{{ message.text }}</p>
                                    <p class="chat-text"><img class="w-full max-w-xs" :src="message.image" alt="images"></p>
                                </div>
                                <div class="chat-group-text" v-else>
                                    <p class="chat-text" v-if="message.text">{{ message.text }}</p>
                                    <p class="chat-text" v-if="message.image"><img class="w-full max-w-xs"
                                            :src="message.image" alt="images"></p>
                                </div>
                                <div class="chat-group-meta">
                                    <span class="chat-meta">{{ message.reply_at }}</span>
                                </div>
                            </div>
                        </li>
                    </div>
                </ul>
                <form @submit.prevent="save">
                    <div class="chat-footer">
                        <label for="media" class="chat-footer-file-label">
                            <i class="lab lab-image lab-font-size-32 text-primary"></i>
                            <input id="media" @change="changeImage" ref="imageProperty" type="file"
                                class="chat-footer-file-input" accept="image/png, image/jpeg, image/jpg">
                        </label>
                        <div class="chat-footer-data">
                            <ul @click.prevent="deleteImage" class="chat-footer-data-list hidden"></ul>
                            <input v-model="chatProps.form.text" type="text" placeholder="Type a message"
                                class="chat-footer-data-input">
                        </div>
                        <button type="submit" class="chat-footer-sent">
                            <i class="lab lab-send-2 lab-font-size-32"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script>

import alertService from "../../../../services/alertService";
import askEnum from "../../../../enums/modules/askEnum";
import statusEnum from "../../../../enums/modules/statusEnum";
import LoadingComponent from "../../components/LoadingComponent";
import { Carousel, Navigation, Pagination, Slide } from "vue3-carousel";

export default {
    name: "ChatComponent",
    components: {
        LoadingComponent,
        Carousel,
        Slide,
        Pagination,
        Navigation
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                askEnum: askEnum,
            },
            chatProps: {
                form: {
                    message_id: "",
                    branch_id: "",
                    user_id: "",
                    is_read: "",
                    text: "",
                    receiver_id: "",
                }
            },
            userMessages: {},
            messages: {},
            user: {},
            customer: {},
            defaultProps: {
                branch: null,
                id: null,
            },
            image: "",
            branchSettings: {
                itemsToShow: 3.5,
                wrapAround: false,
                snapAlign: "start"
            },
            branchBreakpoints: {
                200: {
                    itemsToShow: 1.1,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                250: {
                    itemsToShow: 1.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                300: {
                    itemsToShow: 1.4,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                375: {
                    itemsToShow: 1.7,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                540: {
                    itemsToShow: 2.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                700: {
                    itemsToShow: 3.5,
                    wrapAround: false,
                    snapAlign: 'start',
                }
            }
        };
    },
    mounted() {
        this.branchList();
        const customer = this.$store.getters.authInfo;
        if (Object.keys(this.$route.query).length > 0) {
            this.defaultProps.branch = parseInt(this.$route.query.id);
            this.customer = customer;
            this.chatProps.form = {
                user_id: customer.id,
            };
            this.messageList(customer);
        } else {
            this.loading.isActive = true;
            this.$store.dispatch('frontendSetting/lists').then(res => {
                this.loading.isActive = false;
                this.defaultProps.branch = res.data.data.site_default_branch;
                this.customer = customer;
                this.chatProps.form = {
                    user_id: customer.id,
                };
                this.messageList(customer);
            }).catch((err) => {
                this.loading.isActive = false;
            });
        }
    },
    computed: {
        branches: function () {
            return this.$store.getters["branch/lists"];
        },
        authBranch: function () {
            return this.$store.getters.authBranchId;
        },
        message: function () {
            return this.$store.getters["frontendMessage/lists"];
        }
    },
    methods: {
        branchList: function () {
            this.loading.isActive = true;
            this.$store.dispatch("branch/lists", {
                paginate: 0,
                order_column: "id",
                order_type: "asc",
                status: statusEnum.ACTIVE,
            }).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        branchId: function (branchId) {
            this.defaultProps.branch = branchId;
            this.messageList(this.customer);

        },
        changeImage: function (e) {
            this.image = e.target.files[0];
        },
        messageList: function (customer) {
            this.chatProps.form.receiver_id = customer.id;
            this.user = customer;

            this.loading.isActive = true;
            this.$store.dispatch("frontendMessage/lists", {
                branch_id: this.defaultProps.branch,
                user_id: customer.id
            }).then((res) => {
                this.messages = res.data.data;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
            this.defaultProps.id = customer.id;
        },
        save: function (e) {
            if (typeof this.chatProps.form.text !== "undefined" && this.chatProps.form.text !== "" || this.image != "") {
                this.loading.isActive = true;
                const fd = new FormData();
                fd.append("message_id", this.chatProps.form.message_id);
                fd.append("branch_id", this.chatProps.form.branch_id);
                fd.append("user_id", this.chatProps.form.user_id);
                fd.append("is_read", this.chatProps.form.is_read);
                fd.append("text", typeof this.chatProps.form.text === "undefined" ? "" : this.chatProps.form.text);
                fd.append("receiver_id", this.chatProps.form.receiver_id);
                if (this.image) {
                    fd.append("image", this.image);
                }
                this.$store.dispatch("frontendMessage/save", fd).then((res) => {
                    this.chatProps.form.text = "";
                    this.image = "";
                    this.$refs.imageProperty.value = null;
                    let fileList = $(".chat-footer-data-list");
                    let fileItem = $(".chat-footer-data-item");
                    fileItem.remove();
                    fileList[0].classList.add('hidden');
                    this.messageList(this.defaultProps);
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                });
            }
        },
        deleteImage: function () {
            this.image = "";
        }
    },
    watch: {
        messages: {
            deep: true,
            handler(message) {
                if (message.length > 0) {
                    if (message[0].message !== "undefined") {
                        this.userMessages = message[0].message;
                        this.chatProps.form.message_id = message[0].id;
                        this.chatProps.form.branch_id = message[0].branch_id;
                        this.chatProps.form.is_read = this.enums.askEnum.NO;
                    }
                } else {
                    this.userMessages = {};
                    this.chatProps.form.message_id = "";
                    this.chatProps.form.branch_id = this.defaultProps.branch;
                    this.chatProps.form.is_read = this.enums.askEnum.NO;
                }
            }
        }
    },
}
</script>
