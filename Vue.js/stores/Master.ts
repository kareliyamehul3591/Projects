
import globalConfig from '@/helpers/globalConfig';
import axios from 'axios';

export default class Master {

    url: string;
    path: string;
    fullUrl: string;
    projects?: IManagerProject[] | null = null;

    constructor() {
        this.url = globalConfig.url;
        this.path = globalConfig.path;
        this.fullUrl = `${globalConfig.url}/${globalConfig.path}`;
    }

    getProjects() {
        return axios.get(`${this.fullUrl}/tenants`)
        .then((response) => {this.projects = response.data; return this.projects; })
        .catch((error) => error);
    }

    getSpecificProject(projectId: number | string) {
        return axios.get(`${this.fullUrl}/tenants/${projectId}`)
        .then((response) => response.data)
        .catch((error) => error);
    }

    createProject(project: string) {
        return axios.post(`${this.fullUrl}/tenants`, {project})
            .then((response) => response.data);
    }

    deleteProject(projectId: number | string) {
        return axios.delete(`${this.fullUrl}/tenants/${projectId}`)
            .then((response) => response.data);
    }
}
