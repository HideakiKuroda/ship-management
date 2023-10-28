import axios from 'axios';

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// AxiosのデフォルトヘッダにX-CSRF-TOKENを設定
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

export default axios;