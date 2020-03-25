require ('../bootstrap');
import AuthRepository from "./authRepository";
import CommonRepository from "./commonRepository";
import UserRepository from "./userRepository";

const AuthRepositoryWithAxios = AuthRepository(window.axios);
const authRepository = AuthRepositoryWithAxios('/api/v1/auth');

const UserRepositoryWithAxios = UserRepository(window.axios);
const userRepository = UserRepositoryWithAxios('/api/v1/user');

const CommonRepositoryWithAxios = CommonRepository(window.axios);
const articleRepository = CommonRepositoryWithAxios('/api/v1/user/article');

const homeRepository = CommonRepositoryWithAxios('/api/v1');

const repositories = {
    article: articleRepository,
    auth: authRepository,
    user: userRepository,
    home: homeRepository,
};

export const RepositoryFactory = {
    get: name => repositories[name]
};