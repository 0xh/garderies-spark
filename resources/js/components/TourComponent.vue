<template>
    <v-tour name="myTour" :steps="steps">
        <template slot-scope="tour">
            <transition name="fade">
                <v-step
                        v-if="tour.currentStep === index"
                        v-for="(step, index) of tour.steps"
                        :key="index"
                        :step="step"
                        :previous-step="tour.previousStep"
                        :next-step="tour.nextStep"
                        :stop="tour.stop"
                        :is-first="tour.isFirst"
                        :is-last="tour.isLast"
                >
                    <template>
                        <div slot="actions">
                            <button @click="tour.stop" v-if="!tour.isLast" class="v-step__button">Passer le tour
                            </button>
                            <button @click="tour.previousStep" v-if="!tour.isFirst" class="v-step__button">Précédent
                            </button>
                            <button @click="tour.nextStep" v-if="!tour.isLast" class="v-step__button">Suivant</button>
                            <button @click="tour.stop" v-if="tour.isLast" class="v-step__button">Terminer</button>
                        </div>
                    </template>
                </v-step>
            </transition>
        </template>
    </v-tour>
</template>

<script>
    import VueTour from 'vue-tour';
    import 'vue-tour/dist/vue-tour.css';
    import swal from 'sweetalert2';
    import 'sweetalert2/dist/sweetalert2.min.css'

    export default {
        data() {
            return {
                steps: [
                    {
                        target: '.dashboard__summary',
                        content: "Ceci est votre tableau de bord en tant que réseau de garderies."
                    },
                    {
                        target: '.v-step-0',  // We're using document.querySelector() under the hood
                        content: "Gardez un oeil sur tous vos garderies"
                    },
                    {
                        target: '.v-step-1',
                        content: "Gérez vos employées"
                    },
                    {
                        target: '.v-step-2',
                        content: "Surveillez les événements de votre réseau",
                    },
                    {
                        target: '.v-step-3',
                        content: "Des rapports personnalisés vous informent sur les activités au sein de votre réseau.",
                        params: {
                            placement: 'right'
                        }
                    },
                    {
                        target: '.link-networks',
                        content: "Vous gérez plusieurs garderies ? Organisez-les en réseaux pour plus de clareté !"
                    },
                    {
                        target: '.link-nurseries',
                        content: "Vos garderies."
                    },
                    {
                        target: '.link-users',
                        content: "Gérez ici tous vos employés."
                    },
                    {
                        target: '.link-bookings',
                        content: "Les demandes de remplacements ainsi que ceux planifiés sont répertoriés ici."
                    },
                    {
                        target: '.link-availabilities',
                        content: "Recherchez des remplaçants grâce à notre moteur de recherche et organisez le remplacement de vos ressources."
                    },
                ]
            }
        },
        mounted: function () {

            if (window.location.hash === '#tour') {
                swal({
                    type: 'info',
                    title: 'Bienvenue',
                    text: 'Désirez-vous suivre la présentation assistée ?',
                    showCancelButton: true,
                    confirmButtonText: "Oui !",
                    cancelButtonText: "Non merci"
                }).then((response) => {
                    console.log(response);

                    if (response.value) {
                        this.$tours['myTour'].start();
                        $('.alert-guided-tour').hide();
                    }
                });
            }
        },
        components: {VueTour}
    }
</script>

<style lang="scss">
    $blue: #1976d2;

    #app .v-step {
        z-index: 1;
        background: $blue;
        max-width: 380px;
        padding: 2rem;

        &[x-placement^=top] .v-step__arrow {
            border-top-color: $blue;
        }
        &[x-placement^=bottom] .v-step__arrow {
            border-bottom-color: $blue;
        }
        &[x-placement^=left] .v-step__arrow {
            border-left-color: $blue;
        }
        &[x-placement^=right] .v-step__arrow {
            border-right-color: $blue;
        }
    }
</style>