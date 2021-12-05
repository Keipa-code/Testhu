import React, {createContext, FC, useContext} from "react";
import {NewTestStore} from "./pages/NewTest/NewTestStore";
import {QuestionFormStore} from "./components/Question/QuestionFormStore";
import {TagsFormStore} from "./components/TagsForm/TagsFormStore";

type RootStateContextValue = {
    newTestStore: NewTestStore,
    questionFormStore: QuestionFormStore,
    tagsFormStore: TagsFormStore
}

const RootStateContext = createContext<RootStateContextValue>({} as RootStateContextValue)

const newTestStore = new NewTestStore()
const questionFormStore = new QuestionFormStore()
const tagsFormStore = new TagsFormStore()

export const RootStateProvider: FC<React.PropsWithChildren<{}>> = ({
    children,
}) => {
    return (
        <RootStateContext.Provider value={{
            newTestStore,
            questionFormStore,
            tagsFormStore
        }}>
            { children }
        </RootStateContext.Provider>
    )
}

export const useRootStore = () => useContext(RootStateContext)