import React, {createContext, FC, useContext} from "react";
import {NewTestStore} from "./pages/NewTest/NewTestStore";
import {QuestionFormStore} from "./components/Question/QuestionFormStore";

type RootStateContextValue = {
    newTestStore: NewTestStore,
    questionFormStore: QuestionFormStore
}

const RootStateContext = createContext<RootStateContextValue>({} as RootStateContextValue)

const newTestStore = new NewTestStore()
const questionFormStore = new QuestionFormStore()

export const RootStateProvider: FC<React.PropsWithChildren<{}>> = ({
    children,
}) => {
    return (
        <RootStateContext.Provider value={{ newTestStore, questionFormStore }}>
            { children }
        </RootStateContext.Provider>
    )
}

export const useRootStore = () => useContext(RootStateContext)