<template>
    <a v-if="isAnchor" :class="[sizeClass, baseClasses]">
        <slot />
    </a>
    <Link v-else-if="isLink" :class="[sizeClass, baseClasses]">
        <slot/>
    </Link>
    <button v-else v-bind="$attrs"  :class="[sizeClass, baseClasses]">
        <slot />
    </button>
</template>

<script>
export default {
    name: "PrimaryButton",
    props: {
        size: {
            default: "m",
        },
        isLink: {
            default: false,
        },
        isAnchor:{
            default: false
        }
    },


    computed: {
        sizeClass() {
            switch (this.size){
                case "l":
                    return "px-3 py-2 md:py-3 md:px-5 text-md"
                case "s":
                    return "px-2 py-1 text-sm"
                default:
                    return "px-3 py-2 text-sm"
            }
        },

        baseClasses(){
            return "rounded-md bg-green-600 font-semibold text-white shadow-sm hover:bg-green-700 text-center disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-green-600";
        }

    }
}


</script>

