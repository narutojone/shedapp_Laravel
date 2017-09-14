<template>

    <div class="item col-xs-4 col-md-3">
        <div class="thumbnail">
            <popover effect="scale" v-bind:header="false" placement="right" trigger="click" class="pointer">
                <div slot="content">
                    <pop-img ref="popImg"
                             :source="image"
                             :src="image.publicPath">
                    </pop-img>
                </div>

                <div style="width: 100%; height: 50%; position: relative;">
                    <img v-bind:src="image.publicPath"
                         class="image-preview"
                         v-bind:alt="item.name">
                </div>

            </popover>

            <div class="caption">
                <h4 class="group inner list-group-item-heading">{{ item.name }}</h4>
                <span class="group inner list-group-item-text">{{ item.description }}</span>
            </div>
            <div class="col-xs-12 show-button">
                <button class="col-xs-12 btn btn-sm btn-success" v-on:click="showCategory(item)">
                    List <span class="badge">{{ item.count }}</span>
                </button>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import Popover from 'vue-strap/src/Popover.vue'
    import PopImg from 'src/components/ui/PopImg.vue'

    export default {
        data() {
            return {
            }
        },
        components: {
            Popover,
            PopImg
        },
        props: {
            item: {
                default() {
                    return {}
                }
            }
        },
        computed: {
            image() {
                let image = {}
                if (_.isArray(this.item.files) && this.item.files.length > 0) {
                    image = _.cloneDeep(_.last(this.item.files))
                    return image
                }

                return {
                    publicPath: '/images/logo_lock.png',
                    previewHeight: 256,
                    previewWidth: 256,
                    width: 256,
                    height: 256
                }
            }
        },
        methods: {
            showCategory(item) {
                this.$emit('show-category', item)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

    div.thumbnail {
        text-align: center;
        margin-bottom: 0px;
        // padding: 0;
        padding: 3px;
        -webkit-border-radius: 0px;
        -moz-border-radius: 0px;
        border-radius: 0px;

        /* trying to set the same height for all elements */
        height: 200px;
        max-width: 200px;

        .caption {
            height: 35%;
            max-height: 35%;
            overflow-y: auto;
            padding: 0;
        }

        .image-preview {
            max-height: 100%;
            max-width: 100%;
            padding: 3px;

            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
        }
    }

    .show-button {
        height: 15%;
        padding: 0;

        button {
            height: 100%;
            border-radius: 0px !important;
        }
    }

    .item {
        padding: 0.3em 5px 0 5px;
    }

    .list-group-item-heading, .list-group-item-text {
        margin: 0.2em 0 0 0 !important;
    }

    .item.list-group-item {
        float: none;
        width: 100%;
        background-color: #fff;
        margin-bottom: 10px;
    }
    .item.list-group-item:nth-of-type(odd):hover,.item.list-group-item:hover {
        background: #428bca;
    }

    .item.list-group-item .list-group-image {
        margin-right: 10px;
    }
    .item.list-group-item div.thumbnail {
        margin-bottom: 0px;
    }
    .item.list-group-item .caption {
        padding: 9px 9px 0px 9px;
    }
    .item.list-group-item:nth-of-type(odd) {
        background: #eeeeee;
    }

    .item.list-group-item:before, .item.list-group-item:after {
        display: table;
        content: " ";
    }

    .item.list-group-item img {
        float: left;
    }
    .item.list-group-item:after {
        clear: both;
    }
    .list-group-item-text {
        margin: 0 0 11px;
    }
</style>