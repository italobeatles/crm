import './bootstrap';
import 'bootstrap';
import 'admin-lte';
import alertify from 'alertifyjs';

const CrmApp = {
    init() {
        this.configureAlertify();
        this.bindDeleteConfirm();
        this.bindPipelineDnD();
    },

    configureAlertify() {
        alertify.defaults.transition = 'fade';
        alertify.defaults.notifier.position = 'top-right';
        alertify.defaults.glossary.ok = 'Confirmar';
        alertify.defaults.glossary.cancel = 'Cancelar';
    },

    bindDeleteConfirm() {
        document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();

                alertify.confirm(
                    'Confirmar exclusão',
                    'Confirma a exclusão deste registro?',
                    () => form.submit(),
                    () => {},
                );
            });
        });
    },

    bindPipelineDnD() {
        const board = document.querySelector('[data-pipeline-board]');

        if (! board) {
            return;
        }

        let currentCard = null;

        board.querySelectorAll('[data-pipeline-card]').forEach((card) => {
            card.addEventListener('dragstart', () => {
                currentCard = card;
                card.classList.add('dragging');
            });

            card.addEventListener('dragend', () => {
                card.classList.remove('dragging');
                currentCard = null;
                board.querySelectorAll('[data-pipeline-stage]').forEach((stage) => stage.classList.remove('is-drop-target'));
            });
        });

        board.querySelectorAll('[data-pipeline-stage]').forEach((stage) => {
            stage.addEventListener('dragover', (event) => {
                event.preventDefault();
                stage.classList.add('is-drop-target');
            });

            stage.addEventListener('dragleave', () => {
                stage.classList.remove('is-drop-target');
            });

            stage.addEventListener('drop', async (event) => {
                event.preventDefault();
                stage.classList.remove('is-drop-target');

                if (! currentCard) {
                    return;
                }

                const newStage = stage.dataset.stage;

                if (currentCard.dataset.stage === newStage) {
                    return;
                }

                const previousStage = currentCard.dataset.stage;
                const dropZone = stage.querySelector('[data-pipeline-list]');
                const emptyState = dropZone.querySelector('[data-empty-state]');

                currentCard.classList.add('opacity-50');

                try {
                    const response = await window.axios.patch(
                        currentCard.dataset.updateUrl,
                        { etapa: newStage },
                        {
                            withCredentials: true,
                            headers: {
                                Accept: 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-XSRF-TOKEN': decodeURIComponent(this.getCookie('XSRF-TOKEN') ?? ''),
                            },
                        },
                    );

                    dropZone.appendChild(currentCard);
                    currentCard.dataset.stage = newStage;
                    currentCard.querySelector('[data-stage-badge]').textContent = stage.dataset.stageLabel;

                    if (emptyState) {
                        emptyState.remove();
                    }

                    this.refreshPipelineEmptyStates(board);
                    alertify.success(response.data?.message ?? 'Etapa atualizada com sucesso.');
                } catch (error) {
                    currentCard.dataset.stage = previousStage;
                    alertify.error(error.response?.data?.message ?? 'Nao foi possivel mover a oportunidade.');
                } finally {
                    currentCard.classList.remove('opacity-50');
                }
            });
        });
    },

    refreshPipelineEmptyStates(board) {
        board.querySelectorAll('[data-pipeline-stage]').forEach((stage) => {
            const list = stage.querySelector('[data-pipeline-list]');
            const cards = list.querySelectorAll('[data-pipeline-card]');
            const existingEmptyState = list.querySelector('[data-empty-state]');

            if (! cards.length && ! existingEmptyState) {
                const emptyState = document.createElement('p');
                emptyState.className = 'text-muted mb-0';
                emptyState.dataset.emptyState = 'true';
                emptyState.textContent = 'Sem oportunidades.';
                list.appendChild(emptyState);
            }
        });
    },

    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);

        if (parts.length === 2) {
            return parts.pop().split(';').shift();
        }

        return null;
    },
};

document.addEventListener('DOMContentLoaded', () => CrmApp.init());
