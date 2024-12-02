<template>
    <select class="form-control" @change="valueChanged" v-model="selectedLanguage">
        <option v-for="(language, index) in localLanguages" v-bind:key="language.id"  :value="index" :class="{'item-exist': language.exist}">
            {{language.name}}
        </option>
    </select>
</template>
<script>
import stores from '@/stores';

export default {
    props: ['selectedId', 'activeLanguages', 'languages'],
    data() {
        return {
            localLanguages: [],
            selectedLanguage: '',
        };
    },
    mounted() {
        // stores.Language.fetchData(1, 99999999999999, '')
        // .then((response) => {
        //     this.languages = response.data;
        // })
        // .then((response) => {
        //     if (this.selectedId) {
        //         this.selectedLanguage = this.languages.findIndex((element) => element.id === this.selectedId);
        //     }
        // });

    },
    methods: {
        valueChanged() {
            this.$emit('change', this.languages[this.selectedLanguage]);
        },
    },
    watch: {
        selectedId(newValue) {
            if (newValue) {
                this.selectedLanguage = this.languages.findIndex((element) => element.id === newValue);
            }
        },
        activeLanguages(newValue) {
            for (const index in this.languages) {
                if (index) {
                   for (const index1 in this.activeLanguages) {
                        if (this.languages[index].id === this.activeLanguages[index1]) {
                        this.languages[index].exist = true;
                        } else {
                        this.languages[index].exist = false;
                        }
                    }
                }
            }
        },
        languages(newValue) {
            this.localLanguages = this.languages;
            this.selectedLanguage = 0;
            this.valueChanged();
        },
    },
};
</script>
<style scoped>
.item-exist {
    color: green;

    /*color: white;*/
}
</style>
