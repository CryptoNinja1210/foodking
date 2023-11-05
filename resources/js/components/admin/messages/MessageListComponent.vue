<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card flex flex-col sm:flex-row items-start">
            <div class="w-full sm:w-60 md:w-80 flex-shrink-0 border-b sm:border-r border-[#D9DBE9]">
                <div class="p-4">
                    <h3 class="capitalize text-[22px] leading-[26px] font-semibold mb-6">{{ $t("menu.messages") }}</h3>
                    <form class="flex items-center w-full h-10 px-3 rounded-lg border bg-[#fcfcfc] border-[#D9DBE9]">
                        <i class="lab lab-search-normal lab-font-size-16"></i>
                        <input type="text" v-model="props.search.name" @change="search" placeholder="Search Customer"
                            class="w-full h-full rounded-r-lg ml-3">
                    </form>
                </div>
                <ul class="h-[400px] sm:h-[calc(100vh_-_290px)] overflow-y-auto thin-scrolling db-message-list">
                    <li @click="messageList(customer)"
                        class="px-4 py-2 flex items-center gap-3 transition cursor-pointer hover:bg-[#F7F7FC]"
                        v-if="customers.length > 0" v-for="customer in customers" :key="customer"
                        :class="customer.messages > 0 ? 'unread-message' : '' || customer.id === user.id ? 'active' : ''">
                        <img class="flex-shrink-0 w-9 rounded" :src="customer.image" alt="avatar">
                        <h4 class="text-sm font-medium leading-4 capitalize">{{
                            customer.name
                        }}
                            <span class="block mt-2 text-xs font-normal leading-[14px] text-paragraph">{{
                                customer.phone
                            }}</span>
                        </h4>
                    </li>
                </ul>
            </div>

            <div class="w-full overflow-hidden">
                <div class="py-3 px-4 flex items-center gap-3 border-b border-[#D9DBE9]">
                    <img v-if="user.image" class="flex-shrink-0 w-9 rounded" :src="user.image" alt="avatar">
                    <h4 class="text-sm font-medium leading-4 capitalize">{{ user.name }}
                        <span class="block mt-2 text-xs font-normal leading-[14px] text-paragraph">{{
                            user.phone
                        }}</span>
                    </h4>
                </div>
                <ul class="chat-list backend">
                    <div v-for="message in userMessages" :key="message">
                        <li class="chat-item chat-admin" v-if="message.user_id === user.id">
                            <img class="chat-avatar" :src="message.user_image" alt="avatar">
                            <div class="chat-group">
                                <div class="chat-group-text" v-if="message.text && message.image">
                                    <p class="chat-text">{{ message.text }}</p>
                                    <p class="chat-text"><img class="w-full max-w-xs" :src="message.image" alt="images">
                                    </p>
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
                            <img class="chat-avatar" v-if="message.text || message.image" :src="message.user_image"
                                alt="avatar">
                            <div class="chat-group">
                                <div class="chat-group-text" v-if="message.text && message.image">
                                    <p class="chat-text">{{ message.text }}</p>
                                    <p class="chat-text"><img class="w-full max-w-xs" :src="message.image" alt="images">
                                    </p>
                                </div>
                                <div class="chat-group-text" v-else>
                                    <p class="chat-text" v-if="message.text">{{ message.text }}</p>
                                    <p class="chat-text" v-if="message.image"><img class="w-full max-w-xs"
                                            :src="message.image" alt="images">
                                    </p>
                                </div>
                                <div class="chat-group-meta">
                                    <span class="chat-meta" v-if="message.text || message.image">
                                        {{ message.reply_at }}
                                    </span>
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
                            <input type="text" v-model="props.form.text" placeholder="Type a message"
                                class="chat-footer-data-input">
                        </div>
                        <button type="submit" class="chat-footer-sent">
                            <i class="lab lab-send-2 lab-font-size-32"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import TableLimitComponent from "../components/TableLimitComponent";
import askEnum from "../../../enums/modules/askEnum";


export default {
    name: "MessageListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        LoadingComponent,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                askEnum: askEnum,
            },
            props: {
                form: {
                    message_id: "",
                    branch_id: "",
                    user_id: "",
                    is_read: "",
                    text: "",
                    receiver_id: "",
                },
                search: {
                    paginate: 0,
                    order_column: "id",
                    order_type: "asc",
                    name: "",
                },
            },
            messages: {},
            userMessages: {},
            user: {},
            defaultProps: {
                branch_id: null,
                id: null,
            },
            image: "",

        };
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("defaultAccess/show").then((res) => {
            this.props.form.branch_id = res.data.data.branch_id;
            this.defaultProps.branch_id = res.data.data.branch_id;
            this.customerList().then(res => {
                this.messageList(this.defaultProps);
            });
        }).catch((err) => {
            this.loading.isActive = false;
        });

        const res = this.$store.getters.authInfo;
        this.props.form.user_id = res.id;


    },
    computed: {
        defaultAccess: function () {
            return this.$store.getters["defaultAccess/show"];
        },
        customers: function () {
            return this.$store.getters["customer/lists"];
        },
    },
    methods: {
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        search: function () {
            this.customerList();
        },
        clear: function () {
            this.props.search.name = "";
            this.list();
        },
        changeImage: function (e) {
            this.image = e.target.files[0];
        },
        customerList: function () {
            this.loading.isActive = true;
            return new Promise((resolve, reject) => {
                this.$store.dispatch("customer/lists", this.props.search).then((res) => {
                    if (res.data.data.length > 0) {
                        this.defaultProps.id = res.data.data[0].id;
                        this.loading.isActive = false;
                        resolve(res.data.data[0].id);
                    } else {
                        this.loading.isActive = false;
                    }
                }).catch((err) => {
                    this.loading.isActive = false;
                    reject(err);
                });
            });
        },
        messageList: function (customer) {
            this.props.form.receiver_id = customer.id;
            this.$store.dispatch("customer/show", customer.id).then((res) => {
                this.user = res.data.data;
            }).catch((err) => {
                this.loading.isActive = false;
            });

            this.$store.dispatch("message/lists", {
                branch_id: Object.keys(this.defaultAccess).length > 0 ? this.defaultAccess.branch_id : this.defaultProps.branch_id,
                user_id: customer.id,
            }).then((res) => {
                this.messages = res.data.data;

                if (res.data.data[0].id) {
                    this.$store.dispatch("message/changeStatus", {
                        message_id: res.data.data[0].id,
                        user_id: customer.id,
                    }).then((res) => {

                    }).catch((err) => {
                        this.loading.isActive = false;
                    });

                }
                this.customerList();
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
            this.defaultProps.id = customer.id;

        },

        save: function (e) {
            if (typeof this.props.form.text !== "undefined" && this.props.form.text !== "" || this.image != "") {
                const fd = new FormData();
                fd.append("message_id", this.props.form.message_id);
                fd.append("branch_id", this.props.form.branch_id);
                fd.append("user_id", this.props.form.user_id);
                fd.append("is_read", this.props.form.is_read);
                fd.append("text", typeof this.props.form.text === "undefined" ? "" : this.props.form.text);
                fd.append("receiver_id", this.props.form.receiver_id);
                if (this.image) {
                    fd.append("image", this.image);
                }
                this.loading.isActive = true;
                this.$store.dispatch("message/save", fd).then((res) => {
                    this.props.form.text = "";
                    this.image = "";
                    this.$refs.imageProperty.value = null;
                    let fileList = $(".chat-footer-data-list");
                    let fileItem = $(".chat-footer-data-item");
                    fileItem.remove();
                    fileList[0].classList.add('hidden');
                    this.messageList({
                        branch_id: this.props.form.branch_id,
                        id: this.props.form.receiver_id,
                    });
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
                        this.props.form.message_id = message[0].id;
                        this.props.form.branch_id = message[0].branch_id;
                        this.props.form.is_read = this.enums.askEnum.NO;
                    }
                } else {
                    this.$store.dispatch("defaultAccess/show").then((res) => {
                        this.userMessages = {};
                        this.props.form.message_id = "";
                        this.props.form.branch_id = res.data.data.branch_id;
                        this.props.form.is_read = this.enums.askEnum.NO;
                    }).catch((err) => {
                        this.loading.isActive = false;
                    });
                }
            }
        },

    },
};

</script>
<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>
