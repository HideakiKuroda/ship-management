import { router } from '@inertiajs/vue3';
import axios from 'axios';

export const editItem = (id) => {
axios.get(route('ships.edit', { ship: id }))
.then(response => {
router.get(route('ships.edit', { ship: id }));
})
.catch(error => {
if (error.response && error.response.status === 403) {
alert('申し訳ありません。編集は担当者か権限者のみです！');
} else {
alert('予期せぬエラーが発生しました！');
}
});
};
