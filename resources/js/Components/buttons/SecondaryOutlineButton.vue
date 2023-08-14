<template>
    <Link v-if="isLink" :class="[sizeClass, baseClasses]">
        <slot/>
    </Link>
    <button v-bind="$attrs" v-else :class="[sizeClass, baseClasses]">
        <slot />
    </button>
</template>

<script>
export default {
    name: "SecondaryOutlineButton",
    props: {
        size: {
            default: "m",
        },
        isLink: {
            default: false,
        }
    },


    computed: {
        sizeClass() {
            switch (this.size){
                case "l":
                    return "px-3 py-2 md:py-4 md:px-5 text-md"
                case "s":
                    return "px-2 py-1 text-sm"
                default:
                    return "px-3 py-2 text-sm"
            }
        },

        baseClasses(){
            return "rounded-md bg-inherit border-dark-light border-2 font-semibold text-inherit dark:text-white shadow-sm hover:bg-dark-light hover:text-white";
        }

    }
}


</script>

