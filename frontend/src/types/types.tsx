

export interface ITag {
    id: number;
    tagName: string;
}

export interface ITimeLimit {
    hour?: string;
    minute?: string;
}

export interface ITest {
    id?: number;
    testName?: string;
    description?: string;
    rules?: string;
    date?: string;
    timeLimit?: ITimeLimit;
    tags?: ITag[];
    done?: number;
    passed?: number;
    link?: string;
}

export interface IPagination {
    "@id"?: string;
    "hydra:first": string;
    "hydra:last": string;
    "hydra:next": string;
    "hydra:previous": string;
}



